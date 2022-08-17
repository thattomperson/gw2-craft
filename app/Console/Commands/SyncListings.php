<?php

namespace App\Console\Commands;

use App\Models\Listing;
use GW2Treasures\GW2Api\GW2Api;
use GW2Treasures\GW2Api\V2\Endpoint;
use GW2Treasures\GW2Api\V2\Pagination\Exception\PageOutOfRangeException;
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

  public function getPage(Endpoint $endpoint, $page)
  {
    try {
      return $endpoint->page($page);
    } catch (PageOutOfRangeException $e) {
      return null;
    }
  }

  public function checkForNewListings(GW2Api $client)
  {
    $this->output->info('Checking for new listings');

    $count  = count($client->commerce()->listings()->ids());
    $bar = $this->output->createProgressBar($count);
    $page = 1;
    while ($listings = $this->getPage($client->commerce()->listings(), $page)) {
      DB::table('listings')
      ->upsert(
        collect($listings)
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

      $page++;
      $bar->advance(count($listings));
    }

    $bar->finish();
  }
}
