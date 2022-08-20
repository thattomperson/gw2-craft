<?php

namespace App\Console\Commands;

use App\Exceptions\Handler;
use GuzzleHttp\Client;
use GW2Treasures\GW2Api\GW2Api;
use GW2Treasures\GW2Api\V2\Bulk\IBulkEndpoint;
use GW2Treasures\GW2Api\V2\Pagination\Exception\PageOutOfRangeException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JsonException;
use Sentry\Laravel\SentryHandler;
use Sentry\SentrySdk;
use Throwable;

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
    $client = new Client();
    $this->output->info('Checking for new listings');

    $bar = $this->output->createProgressBar();
    $page = 1;
    DB::disableQueryLog();
    DB::unsetEventDispatcher();
    while ($listings = $this->getPage($client, $page)) {
      $values = array_map(function ($listing) {
        try {
        $buy = json_encode($listing->buys ?? [], JSON_THROW_ON_ERROR);
        $sell = json_encode($listing->sells ?? [], JSON_THROW_ON_ERROR);
          return [
            'id' => Str::uuid(),
            'remote_item_id' => $listing->id,
            'buy' => $buy,
            'sell' => $sell,
            'created_at' => now(),
            'updated_at' => now(),
          ];
        } catch (JsonException $e) {
          $this->output->error($e);
        } finally {
          unset($buy, $sell);
          unset($listing);
        }
      }, $listings);



      DB::table('listings')
      ->upsert(
        $values,
        'remote_item_id',
        ['buy', 'sell', 'updated_at']
      );

      $page++;
      $bar->advance(count($listings));
      unset($listings);
    }

    $bar->finish();

    return 0;
  }

  public function getPage(Client $client, $page)
  {
    try {
      $response = $client->get('https://api.guildwars2.com/v2/commerce/listings?page=' . $page . '&page_size=200');
      $body = $response->getBody();
      return json_decode($body->getContents());
    } catch (Throwable $e) {
      return null;
    } finally {
      if (isset($body)) {
        $body->close();
      }
      unset($body);
      unset($response);
    }
  }
}
