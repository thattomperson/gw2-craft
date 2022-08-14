<?php

namespace App\Console\Commands;


use App\Models\Item;
use GW2Treasures\GW2Api\GW2Api;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all obj from GW2 api';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('sync:items', [], $this->getOutput());
        Artisan::call('sync:recipes', [], $this->getOutput());
        
    }
}
