<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'image_path',
        'price',
        'category_id',
        'sku',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }


    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details', 'product_id', 'order_id')
            ->withPivot('quantity'); // Để lấy thông tin số lượng sản phẩm
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
