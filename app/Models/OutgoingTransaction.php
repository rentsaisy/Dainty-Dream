<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'user_id',
        'quantity',
        'unit_price',
        'total_price',
        'invoice_number',
        'notes',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * Product relationship.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Customer relationship.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * User (employee) relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
