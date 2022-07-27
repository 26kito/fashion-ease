<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'varian',
        'description',
        'image',
        'price',
    ];

    use HasFactory;
    
    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function detailsProduct() {
        return $this->hasMany(DetailProduct::class, 'dp_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
