<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'status',
    ];

    /**
     * Outgoing transactions (sales) from this customer.
     */
    public function outgoingTransactions()
    {
        return $this->hasMany(OutgoingTransaction::class);
    }
}
