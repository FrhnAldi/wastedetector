<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Tambahan untuk mengatasi error P1132

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'category',
        'confidence',
        'bbox',
        'image_path',
    ];

    protected $casts = [
        'confidence' => 'float',
    ];

    /**
     * Scope for B3 detections.
     */
    public function scopeB3(Builder $query)
    {
        return $query->where('category', 'B3');
    }

    /**
     * Scope for Non-B3 detections.
     */
    public function scopeNonB3(Builder $query)
    {
        return $query->where('category', 'Non-B3');
    }
}