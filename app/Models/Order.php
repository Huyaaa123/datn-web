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
        'variants',
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
        return $this->belongsTo(User::class, 'user_id'); // Liên kết với bảng users qua cột user_id
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
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details', 'order_id', 'product_id')
                    ->withPivot('quantity'); // Để lấy thông tin số lượng sản phẩm
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_code', 'code'); // Liên kết đơn hàng với voucher qua mã voucher
    }
    public function getTotalAmountWithoutDiscountAttribute()
    {
        return $this->orderDetails->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function getTotalAmountAfterDiscountAttribute()
    {
        $total = $this->total_amount_without_discount;
        if ($this->voucher) {
            $total -= $this->voucher->discount_amount;
        }
        return $total;
    }


}
