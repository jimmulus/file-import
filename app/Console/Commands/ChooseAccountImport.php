<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Jobs\PrepareFile;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Storage;

class ChooseAccountImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import file and create Accounts';

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
     * @return void
     */
    public function handle()
    {
        $file = $this->choice(
            'Which file do you want to import (json is default)',
            ['challenge.json', 'challenge.csv', 'challenge.xlsx', 'fake_file.xls'],
            0
        );
        if (Storage::disk('import')->exists($file)) {
            $path = Storage::disk('import')->path($file);
            $this->info('Importing Accounts File: '.$file.' ...');
            $batch = Bus::batch([])->then(function (Batch $batch) {
                Log::channel('validation')->info('importFille', ['done' => $batch->id]);
            })->dispatch();
            $batch->add(new PrepareFile($path));
            $this->info('Batch ID: '. $batch->id);
        } else {
            $this->info('File: '.$file.' is not found.');
        }
    }
}
