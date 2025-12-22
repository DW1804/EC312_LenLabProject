<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false; // Tắt timestamps vì bảng không có created_at, updated_at
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total_amount', // Sử dụng total_amount thay vì total
        'order_note',   // Sử dụng order_note thay vì notes
        'transfer_image',
        // Address columns (thay thế shipping_address JSON)
        'email',
        'full_name',
        'phone',
        'province',
        'ward',         // Thêm cột ward
        'specific_address'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2' // Sử dụng total_amount thay vì total
    ];

    /**
     * Boot the model and generate order_id
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_id)) {
                $order->order_id = self::generateOrderId();
            }
        });
    }

    /**
     * Generate unique order ID
     */
    private static function generateOrderId()
    {
        do {
            $orderId = 'LL' . date('Ymd') . rand(1000, 9999);
        } while (self::where('order_id', $orderId)->exists());
        
        return $orderId;
    }

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    /**
     * Get status color class
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'processing':
                return 'text-yellow-400';
            case 'shipping':
                return 'text-blue-400';
            case 'delivered':
                return 'text-green-400';
            case 'cancelled':
                return 'text-red-400';
            default:
                return 'text-gray-400';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'processing':
                return 'Đang xử lý';
            case 'shipping':
                return 'Đang giao hàng';
            case 'delivered':
                return 'Đã giao hàng';
            case 'cancelled':
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        switch ($this->status) {
            case 'processing':
                return 'schedule';
            case 'shipping':
                return 'local_shipping';
            case 'delivered':
                return 'check_circle';
            case 'cancelled':
                return 'cancel';
            default:
                return 'help';
        }
    }
}