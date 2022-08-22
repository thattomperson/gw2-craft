<?php

namespace App\Console\Support;

use GW2Treasures\GW2Api\V2\Bulk\IBulkEndpoint;
use GW2Treasures\GW2Api\V2\Pagination\IPaginatedEndpoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionClass;
use stdClass;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Syncer {
  protected IPaginatedEndpoint $endpoint;
  protected Syncable & Model $model;
  protected OutputInterface $output;

  public function __construct(
    IPaginatedEndpoint $endpoint,
    Syncable & Model $model,
    OutputInterface $output = null
  ) {
    $this->endpoint = $endpoint;
    $this->model = $model;
    $this->output = $output ?? new NullOutput();
  }

  protected function id(string $namespace, string $name): UuidInterface
  {
    return  Uuid::uuid5(Uuid::uuid5(Uuid::fromInteger("0"), $namespace), $name);
  }

  protected function values(Model $model, $values, $parent_id = null): array
  {
    $fillable = $model->getFillable();
    if (is_null($parent_id)) $fillable[] = 'id';

    return collect($fillable)->mapWithKeys(function ($key) use ($model, $values) {
      if ($key === 'id') return ['id' => $this->id($model->getTable(), $values['id'])];
      if ($key === 'created_at') return ['created_at' => now()];
      if ($key === 'updated_at') return ['updated_at' => now()];

      $this->model->setAttribute($key, $values[$key === 'remote_id' ? 'id' : $key] ?? null);
      return [$key => $this->model->getAttributes()[$key]];
    })->toArray();
  }

  protected function relationships(Model $model, $values, string $localKey): array
  {
    return collect($values)
      ->filter(fn ($_, $key) => $model->isRelation($key))
      ->mapWithKeys(function ($values, $key) use ($model, $localKey) {
        /** @var HasOneOrMany $relation */
        $relation = $model->{$key}();
        $related = $relation->getRelated();

        return [
          $related->getTable() => Arr::map($values, function ($values, $index) use ($related, $relation, $localKey) {
            return array_merge(
              [
                'id' => $this->id($related->getTable(), $localKey . $index),
                $relation->getForeignKeyName() => $localKey,
              ],
              $this->values($related, (array) $values, $localKey),
            );
          }),
        ];
      })->toArray();
  }

  public function sync($update = null)
  {
    DB::disableQueryLog();
    DB::unsetEventDispatcher();

    $count = 0;
    if ($this->endpoint instanceof IBulkEndpoint) {
      $count = count($this->endpoint->ids());
    }

    $bar = new ProgressBar($this->output, $count);

    $bar->setEmptyBarCharacter('░'); // light shade character \u2591
    $bar->setProgressCharacter('');
    $bar->setBarCharacter('▓'); // dark shade character \u2593

    $update ??= Arr::except($this->model->getFillable(), ['remote_id', 'id', 'created_at']);

    $this->endpoint->batch($parallelRequests = null, function ($results) use ($bar, $update) {
      $related = [];
      $values = Arr::map($results, function ($result) use (&$related) {
        $values = $this->values($this->model, (array) $result);

        foreach ($this->relationships($this->model, (array) $result, $values['id']) as $table => $relatedValues) {
          $related[$table] ??= [];
          $related[$table] = [...$related[$table], ...$relatedValues];
        };

        return $values;
      });

      DB::table($this->model->getTable())->upsert(
        $values,
        'remote_id',
        Arr::except(array_keys(head($values)), ['id', 'remote_id', 'created_at'])
      );
      foreach ($related as $table => $values) {
        DB::table($table)->upsert(
          $values,
          'id',
          Arr::except(array_keys(head($values)), ['id', 'created_at'])
        );
      }
      $bar->advance(count($results));
    });
    $bar->finish();
  }
}