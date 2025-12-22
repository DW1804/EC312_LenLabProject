<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DigitalProductPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'digital_product_id',
        'customer_email',
        'customer_name',
        'order_code',
        'amount_paid',
        'purchased_at',
        'expires_at',
        'downloads_count',
        'email_sent',
        'download_history'
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'email_sent' => 'boolean',
        'download_history' => 'array',
        'amount_paid' => 'decimal:2'
    ];

    public function digitalProduct()
    {
        return $this->belongsTo(DigitalProduct::class);
    }

    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->isAfter($this->expires_at);
    }

    public function canDownload()
    {
        if ($this->isExpired()) {
            return false;
        }

        return $this->downloads_count < $this->digitalProduct->download_limit;
    }

    public function recordDownload()
    {
        $history = $this->download_history ?? [];
        $history[] = [
            'downloaded_at' => now()->toISOString(),
            'ip_address' => request()->ip()
        ];

        $this->update([
            'downloads_count' => $this->downloads_count + 1,
            'download_history' => $history
        ]);
    }
}