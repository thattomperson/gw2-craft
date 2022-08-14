<?php

namespace App\Console\Commands;

use App\Models\Item;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;

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

        
        $this->withProgressBar(
            $client->items()->all(),
            function ($obj) {
                $item = Item::firstOrNew(['remoteId' => $obj->id]);
                $item->fill((array) $obj);
                $item->save();
            }
        );
    }
}
