<?php

namespace App\Console\Commands;

use App\Models\Item;
use Carbon\Carbon;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SyncItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Items from GW2 api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new GW2Api();

        // $this->checkForNewItems($client);
        
        $itemsIds = Item::orderBy('updated_at', 'asc')->limit(20000)->select('remote_id')->get()->pluck('remote_id')->toArray();
        $items = $client->items()->many($itemsIds);
        
        $this->withProgressBar(
            $items,
            function ($obj) {
                $item = Item::where('remote_id', $obj->id)->first();
                $item->fill((array) $obj);
                $item->save();
            }
        );
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
            $this->output->info("Inserting new items for collection");
            $new_ids->chunk(2000)->each(function ($chunk) {
                DB::table('items')->insert(
                    $chunk
                        ->map(fn ($id) => ['remote_id' => $id, 'updated_at' => Carbon::parse('1970-01-01 00:00:01'), 'created_at' => now()])
                        ->toArray()
                );
            });
        }
        
    }
}
