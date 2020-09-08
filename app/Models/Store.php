<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'name', 'email', 'address', 'lat', 'long'
    ];

    /**
     * Get list product for the store.
     */
    public function store_products()
    {
        return $this->hasMany(StoreProduct::class);
    }

    /**
     * Get list product for the store when create new.
     */
    public function store_products_first()
    {
        return Product::with('category')->get();
    }
}
