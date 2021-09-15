<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'salutation',
        'first_name',
        'last_name',
        'suffix',
        'address',
        'checked',
        'description',
        'interest',
        'date_of_birth',
        'email',
        'account',
    ];

    /**
     * Get the creditcards owned by the account.
     */
    public function creditcards()
    {
        return $this->hasMany(Creditcard::class);
    }
}
