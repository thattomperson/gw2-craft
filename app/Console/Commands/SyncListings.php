<?php

namespace App\Console\Commands;

use App\Models\Listing;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncListings extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sync:listings';

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
    $this->checkForNewListings($client);

    return 0;
  }

  public function checkForNewListings(GW2Api $client)
  {
    $this->output->info('Checking for new listings');

    $listings = collect($client->commerce()->listings()->all());
    if ($listings->isEmpty()) return;

    $this->output->info("Upserting {$listings->count()} records");
    $chunks = $listings->chunk(100);
    $this->withProgressBar($chunks, function ($listings) {
      DB::table('listings')
      ->upsert(
        $listings
          ->map(function ($listing) {
            return [
              'id' => Str::uuid(),
              'remote_item_id' => $listing->id,
              'buy' => json_encode($listing->buys ?? []),
              'sell' => json_encode($listing->sells ?? []),
              'created_at' => now(),
              'updated_at' => now(),
            ];
          })
          ->toArray(),
        'remote_item_id',
        ['buy', 'sell', 'updated_at']
      );
    });
  }
}
