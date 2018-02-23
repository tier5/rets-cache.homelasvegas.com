<?php

namespace App\Console\Commands;

use App\Citylist;
use App\Http\Controllers\SearchController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:property';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Import Rets Property Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Log::info('Queue Started');
            $cont = SearchController::importData();
            Log::info('Queue Created');
        } catch (\Exception $e) {
            Log::info('error controller !! ' . $e->getMessage());
        }
    }
}
