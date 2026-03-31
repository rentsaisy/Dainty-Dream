<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_outgoing_transactions';

    protected $fillable = [
        'product_id',
        'customer_id',
        'user_id',
        'quantity',
        'transaction_date',
        'notes',
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
