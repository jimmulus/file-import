<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class BatchController extends Controller
{
    public function __invoke(string $batch)
    {
        return Bus::findBatch($batch);
    }
}
