<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'variant_id',    // Tận dụng cột có sẵn
        'variant_info',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'variant_info' => 'array',
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}