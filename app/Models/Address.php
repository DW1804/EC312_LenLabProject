<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'province_id',
        'ward_id',
        'specific_address',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Province
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // Relationship with Ward
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    // Get full address string
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->specific_address,
            $this->ward ? $this->ward->name : null,
            $this->province ? $this->province->name : null
        ];

        return implode(', ', array_filter($parts));
    }
}