<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'total_amount',
        'order_date',
        'telephone',
        'shipping_address',
        'payment_method',
        'checkpay',
        'notes',
        'cancel',
        'confirmed',
        'on_delivery',
        'delivered',
        'received',
        'complete',
        'cancelorder',
        'canceled'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function addresses()
    {
        return $this->belongsTo(Address::class);
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_code', 'code'); // Liên kết đơn hàng với voucher qua mã voucher
    }


}
