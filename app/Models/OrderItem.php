<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'price', 'product_id', 'quantity', 'size'
    ];


    /**
     * product 
     *
     * @return Model
     */
    public function product() {
        return $this->belongsTo(product::class);
    }

}
