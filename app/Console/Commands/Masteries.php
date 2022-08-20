<?php

namespace App\Console\Commands;


use App\Models\Item;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Masteries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masteries';

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

        $masteries = $client->masteries()->all();
        $total = 0;
        foreach ($masteries as $mastery) {
            $xp = collect($mastery->levels)->sum->exp_cost;
            $total += $xp;
            $this->output->writeln(sprintf('%s: %d', $mastery->name, $xp));
        }

        $this->output->writeln(sprintf('%s: %d', 'Total', $total));

        return 0;
    }
}
