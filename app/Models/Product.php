<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image', 'product_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'code', 'name', 'description', 'price', 'sale_off', 'sizes', 'status', 'published' 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'published' => 1,
        'sizes' => '[]',
        'images' => '[]'
    ];

    /**
     * Get list product for the order.
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_products')->withPivot('price', 'sale_off');
    }

    /**
     * parent category
     *
     * @return App\Category
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * product is public
     *
     * @param [type] $query
     * @return void
     */
    public function scopeIsPublic($query)
    {
        return $query->where('published' , 1);
    }

    /**
     * Get the image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        return \Arr::first($this->images) ?: 'images/no-image.svg';
    }

    /**
     * product is public
     *
     * @param [type] $query
     * @return void
     */
    public function getProductIdAttribute()
    {
        return $this->id;
    }

}
