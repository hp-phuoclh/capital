<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'order'
    ];

    /**
     * parent category
     *
     * @return App\Category
     */
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the children
     *
     * @return 
     */
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category
     *
     * @return 
     */
    public function products() {
        return $this->hasMany(Product::class);
    }
}
