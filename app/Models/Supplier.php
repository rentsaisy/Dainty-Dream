<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_suppliers';

    protected $fillable = [
        'name',
        'city',
    ];

    /**
     * Products from this supplier.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
