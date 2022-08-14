<?php

namespace App\Console\Commands;


use App\Models\Item;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all obj from GW2 api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('sync:items', [], $this->getOutput());
        Artisan::call('sync:recipes', [], $this->getOutput());
    }

    public function checkForNewItems(GW2Api $client)
    {
        $this->output->info('Checking for new items');
        
        $ids = $client->items()->ids();
        $old_ids = Item::select('remote_id')->get()->pluck('remote_id')->toArray();
        $new_ids = collect(array_diff($ids, $old_ids));
        $count = $new_ids->count();

        $this->output->info("$count new items found");
        if ($count > 0) {
            $chunks = $new_ids->chunk(200);
            $this->output->info("Inserting new items for collection");

            $this->withProgressBar($chunks, function ($chunk) use ($client) {
                $items = collect($client->items()->many($chunk->toArray()));
                

                DB::table('items')->insert(
                    $items
                        ->map(function ($item) {
                            $obj = (array) $item;
                            $obj['remote_id'] = $item->id;
                            
                            $item = new Item($obj);
                            $attributes = $item->getAttributes();
                            $attributes['created_at'] = $attributes['updated_at'] = now();

                            $attributes["id"] = Str::uuid();
                            $attributes["chat_link"] ??= null;
                            $attributes["created_at"] ??= null;
                            $attributes["default_skin"] ??= 0;
                            $attributes["description"] ??= null;
                            $attributes["details"] ??= null;
                            $attributes["flags"] ??= null;
                            $attributes["game_types"] ??= null;
                            $attributes["icon"] ??= null;
                            $attributes["level"] ??= null;
                            $attributes["name"] ??= null;
                            $attributes["rarity"] ??= null;
                            $attributes["remote_id"] ??= null;
                            $attributes["restrictions"] ??= null;
                            $attributes["type"] ??= null;
                            $attributes["updated_at"] ??= null;
                            $attributes["vendor_value"] ??= 0;

                            unset($attributes['upgrades_into'], $attributes['upgrades_from']);

                            ksort($attributes);

                            return $attributes;
                        })
                        ->toArray()
                );
            });
        }
    }
}
