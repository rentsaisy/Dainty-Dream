<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
