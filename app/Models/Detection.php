<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_uuid', 'label', 'category', 'confidence',
        'bbox', 'image_path', 'latitude', 'longitude', 'gps_accuracy',
    ];

    protected $casts = [
        'confidence'   => 'float',
        'latitude'     => 'float',
        'longitude'    => 'float',
        'gps_accuracy' => 'float',
    ];

    // ─── Scopes ───────────────────────────────────────────────

    /** Hanya deteksi milik UUID tertentu */
    public function scopeForUser(Builder $query, string $uuid): Builder
    {
        return $query->where('user_uuid', $uuid);
    }

    /** Hanya deteksi yang punya koordinat GPS */
    public function scopeWithLocation(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /** Hanya sampah B3 */
    public function scopeB3(Builder $query): Builder
    {
        return $query->where('category', 'B3');
    }

    /** Hanya sampah Non-B3 */
    public function scopeNonB3(Builder $query): Builder
    {
        return $query->where('category', 'Non-B3');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function hasLocation(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function googleMapsUrl(): string
    {
        if (! $this->hasLocation()) return '#';
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }
}