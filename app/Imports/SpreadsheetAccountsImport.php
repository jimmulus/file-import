<?php

namespace App\Imports;

use App\Imports\AccountsImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SpreadsheetAccountsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /**
    * @param Collection $rows
    *
    * @return void
    */
    public function collection(Collection $rows) : void
    {
        foreach ($rows as $row) {
            $row['credit_card'] = [
                'type' => $row['credit_cardtype'],
                'number' => $row['credit_cardnumber'],
                'name' => $row['credit_cardname'],
                'expiration_date' => $row['credit_cardexpirationdate'],
            ];

            (new AccountsImport)->create((array) $row);
        }
    }

    /**
    *
    * @return integer
    */
    public function chunkSize(): int
    {
        return 500;
    }
}
