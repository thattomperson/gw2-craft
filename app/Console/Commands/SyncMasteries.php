<?php

namespace App\Console\Commands;

use App\Console\Support\Syncer;
use App\Models\Item;
use App\Models\Mastery;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;

class SyncMasteries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:masteries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Masteries from GW2 api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $client = new GW2Api();
      with(new Syncer($client->masteries(), new Mastery(), $this->getOutput()))->sync();
      return 0;
    }
}
