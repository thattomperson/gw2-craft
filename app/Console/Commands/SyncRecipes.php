<?php

namespace App\Console\Commands;

use App\Console\Support\Syncer;
use App\Models\Recipe;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SyncRecipes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:recipes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $client = new GW2Api();
      with(new Syncer($client->recipes(), new Recipe(), $this->getOutput()))->sync();
      return 0;
    }

    public function checkForNewRecipes(GW2Api $client)
    {
        $this->output->info('Checking for new recipes');

        $ids = $client->recipes()->ids();
        $old_ids = Recipe::select('remote_id')->get()->pluck('remote_id')->toArray();
        $new_ids = collect(array_diff($ids, $old_ids));
        $count = $new_ids->count();

        $this->output->info("$count new items found");
        if ($count > 0) {
            $chunks = $new_ids->chunk(200);
            $this->output->info("Inserting new recipes");

            $this->withProgressBar($chunks, function ($chunk) use ($client) {
                $objs = collect($client->recipes()->many($chunk->toArray()));
                $recipes = [];
                $recipeIngredients = [];
                foreach ($objs as $obj) {
                    $recipe = [];
                    $recipe['id'] = Str::uuid();
                    $recipe['remote_id'] = $obj->id;

                    $recipe['type'] = $obj->type ?? null;
                    $recipe['output_item_id'] = $obj->output_item_id ?? null;
                    $recipe['output_item_count'] = $obj->output_item_count ?? null;
                    $recipe['time_to_craft_ms'] = $obj->time_to_craft_ms ?? null;
                    $recipe['min_rating'] = $obj->min_rating ?? null;
                    $recipe['chat_link'] = $obj->chat_link ?? null;
                    $recipe['created_at'] = $recipe['updated_at'] = now();

                    $recipe['flags'] = json_encode($obj->flags) ?? null;
                    $recipe['disciplines'] = json_encode($obj->disciplines) ?? null;

                    $recipes[] = $recipe;

                    foreach ($obj->ingredients as $ing) {
                        $recipeIngredients[] = [
                            'recipe_id' => $recipe['id'],
                            'remote_item_id' => $ing->item_id,
                            'count' => $ing->count,
                        ];
                    }
                }
                DB::transaction(function () use ($recipes, $recipeIngredients) {
                    DB::table('recipes')->insert($recipes);
                    DB::table('recipe_ingredient')->insert($recipeIngredients);
                });
            });
        }
    }
}
