<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_code',
        'total_amount',
        'payment_method',
        'delivery_address',
        'status',
        'delivery_date',
        'delivered_on_time',
        'notes',
    ];

     public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }




}
