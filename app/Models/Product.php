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
        'description',
        'image_path',
        'price',
        'category_id',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
