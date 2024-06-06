<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dp_id',
        'size',
        'stock'
    ];
    
    public $timestamps = false;

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
