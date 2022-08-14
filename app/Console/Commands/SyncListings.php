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
        
        $ids = $client->commerce()->listings()->ids();
        
        $old_ids = Listing::select('remote_item_id')->get()->pluck('remote_item_id')->toArray();
        $new_ids = collect(array_diff($ids, $old_ids));
        $count = $new_ids->count();

        $this->output->info("$count new listings found");
        if ($count > 0) {
            $chunks = $new_ids->chunk(200);
            $this->output->info("Inserting new listings");

            $this->withProgressBar($chunks, function ($chunk) use ($client) {
                $listings = collect($client->commerce()->listings()->many($chunk->toArray()));

                DB::table('listings')->insert(
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
                        ->toArray()
                );
            });
        }
    }
}
