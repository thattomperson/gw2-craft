<?php

namespace App\Console\Commands;

use App\Models\Item;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-api';

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

      dump($client->masteries()->page(1, 10));
      dd($client->achievements()->page(1, 10));

      return 0;
    }
}
