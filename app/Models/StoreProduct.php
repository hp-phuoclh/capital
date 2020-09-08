<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sp) {
            $sp->{$sp->getKeyName()} = (string) \Str::uuid();
        });
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category', 'image', 'code', 'name', 'description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'product_id', 'stock', 'in_stock', 'price', 'sale_off'
    ];

    /**
     * parent category
     *
     * @return Product
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

    /**
     * parent category
     *
     * @return Store
     */
    public function store() {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        $image = 'images/no-image.svg';
        try {
            $image = $this->product->image;
        } catch(\Exception $e) {
            \Log::error(__FILE__ . $e->getMessage());
        }
        return $image;
    }

    /**
     * Get the category.
     *
     * @return string
     */
    public function getCategoryAttribute()
    {
        $category = null;
        try {
            $category = $this->product->category;
        } catch(\Exception $e) {
            \Log::error(__FILE__ . $e->getMessage());
        }
        return $category;
    }

    /**
     * Get the code.
     *
     * @return string
     */
    public function getCodeAttribute()
    {
        $code = null;
        try {
            $code = $this->product->code;
        } catch(\Exception $e) {
            \Log::error(__FILE__ . $e->getMessage());
        }
        return $code;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $name = null;
        try {
            $name = $this->product->name;
        } catch(\Exception $e) {
            \Log::error(__FILE__ . $e->getMessage());
        }
        return $name;
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $description = null;
        try {
            $description = $this->product->description;
        } catch(\Exception $e) {
            \Log::error(__FILE__ . $e->getMessage());
        }
        return $description;
    }
}
