<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'discount_percent',
        'min_order_value',
        'usage_limit',
        'used',
        'start_date',
        'end_date',
        'status',
    ];

    public function isValid()
    {
        return $this->status == '1' &&
            (!$this->start_date || $this->start_date <= now()) &&
            (!$this->end_date || $this->end_date >= now()) &&
            ($this->used < $this->usage_limit);
    }

    // Kiểm tra nếu đơn hàng đủ điều kiện để áp dụng giảm giá
    public function isApplicable($orderValue)
    {
        return $orderValue >= $this->min_order_value;
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'voucher_details');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_code', 'code'); // Voucher liên kết với Order qua mã voucher
    }
}
