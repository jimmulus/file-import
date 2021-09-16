<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Bus;
use App\Jobs\PrepareFile;
use App\Rules\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => ['required', new FileType($request->file('file'))],
        ]);

        $custom_name = uniqid('accounts_').'.'.$request->file->getClientOriginalExtension();
        $path = Storage::path($request->file('file')->storeAs('import', $custom_name));
        $batch = Bus::batch([])->dispatch();

        $batch->add(new PrepareFile($path));
        return view('file-upload', compact('batch'));
    }
}
