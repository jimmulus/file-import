<?php

namespace App\Jobs;

use App\Imports\SpreadsheetAccountsImport;
use App\Jobs\ProcessImportData;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrepareFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        return $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = $this->getValidImportType();

        if ($accounts != false) {
            $chunks = array_chunk($accounts, 500);
            foreach ($chunks as $row) {
                $this->batch()->add(new ProcessImportData($row));
            }
        }
    }

    /**
     * Get data json file or start excel.
     *
     * @return array|false
     */
    protected function getValidImportType()
    {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);


        if (in_array($extension, ['json', 'txt'])) {
            return json_decode(file_get_contents($this->file), true);
        } elseif (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            \Excel::import(new SpreadsheetAccountsImport, $this->file);
        }
        return false;
    }
}
