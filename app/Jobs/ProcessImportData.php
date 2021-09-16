<?php

namespace App\Jobs;

use App\Imports\AccountsImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImportData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @param array $data
     *
     * @return void
     */
    public function __construct($data)
    {
        return $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param AccountsImport $import
     *
     * @return void
     */
    public function handle(AccountsImport $import)
    {
        $this->batch()->add($import->create($this->data));
    }
}
