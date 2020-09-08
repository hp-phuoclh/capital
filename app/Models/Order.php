<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Brexis\LaravelWorkflow\Traits\WorkflowTrait;

class Order extends Model
{
    use WorkflowTrait;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_string', 'is_completed'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($order) {
            $order->code = strtoupper(bin2hex(openssl_random_pseudo_bytes(3)));
        });
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get list product for the order.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('price', 'quantity', 'size');
    }

    /**
     * Get the user for the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the store for the order.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeAuth($query)
    {
        // check admin
        if( auth()->guard('api_admin')->check()){
            return $query->whereRaw('1 = 1');
        }
        return $query->where('user_id' , auth()->id());
    }

    /**
     * Get the status description.
     *
     * @return string
     */
    public function getStatusStringAttribute()
    {
        $status_enum = \OrderStatus::fromValue((int)$this->status);
        return $status_enum->description;
    }

    /**
     * Get the status description.
     *
     * @return string
     */
    public function getIsCompletedAttribute()
    {
        return $this->completed_at != null;
    }
}
