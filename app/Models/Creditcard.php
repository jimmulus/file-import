<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'number',
        'name',
        'expiration_date',
    ];

    /**
     * Get the account that owns the creditcard.
     */
    public function post()
    {
        return $this->belongsTo(Account::class);
    }
}
