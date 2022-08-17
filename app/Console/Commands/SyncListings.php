<?php

namespace App\Console\Commands;

use GW2Treasures\GW2Api\GW2Api;
use GW2Treasures\GW2Api\V2\Bulk\IBulkEndpoint;
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

  public function getPage(IBulkEndpoint $endpoint, $page)
  {
    try {
      return $endpoint->page($page, 100);
    } catch (PageOutOfRangeException $e) {
      return null;
    }
  }

  public function checkForNewListings(GW2Api $client)
  {
    $this->output->info('Checking for new listings');
    $bar = $this->output->createProgressBar();
    $page = 1;
    while ($listings = $this->getPage($client->commerce()->listings(), $page)) {
      DB::table('listings')
      ->upsert(
        array_map(function ($listing) {
          return [
            'id' => Str::uuid(),
            'remote_item_id' => $listing->id,
            'buy' => json_encode($listing->buys ?? []),
            'sell' => json_encode($listing->sells ?? []),
            'created_at' => now(),
            'updated_at' => now(),
          ];
        }, $listings),
        'remote_item_id',
        ['buy', 'sell', 'updated_at']
      );

      $page++;
      $bar->advance(count($listings));
      unset($listings);
    }

    $bar->finish();
  }
}
