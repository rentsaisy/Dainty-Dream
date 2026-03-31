<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_categories';

    protected $fillable = [
        'name',
    ];

    /**
     * Products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
