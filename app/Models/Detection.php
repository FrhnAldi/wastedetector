<?php
// app/Models/Detection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Detection extends Model
{
    protected $fillable = [
        'user_uuid',
        'label',
        'category',
        'confidence',
        'bbox',
        'image_path',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'confidence' => 'float',
        'bbox'       => 'array',   // ✅ otomatis decode JSON
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    // ✅ Scope: hanya baris yang punya koordinat GPS
    public function scopeWithLocation(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    // ✅ Scope: filter per user uuid
    public function scopeForUser(Builder $query, string $uuid): Builder
    {
        return $query->where('user_uuid', $uuid);
    }
}