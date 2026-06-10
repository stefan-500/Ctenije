<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    public const ADMIN_TIMEZONE = 'Europe/Belgrade';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim((string) $value));
    }

    public function redemptions()
    {
        return $this->hasMany(DiscountCodeRedemption::class);
    }

    public function remainingUses(): ?int
    {
        if ($this->max_uses === null) {
            return null;
        }

        return max(0, $this->max_uses - $this->uses_count);
    }

    /**
     * Discount windows are stored in the app timezone, but admins manage them
     * as Europe/Belgrade local times in the UI.
     */
    public function adminDateTimeInput(string $attribute): ?string
    {
        return $this->{$attribute}?->copy()
            ->timezone(self::ADMIN_TIMEZONE)
            ->format('Y-m-d\TH:i');
    }

    public function adminDateTimeDisplay(string $attribute): ?string
    {
        return $this->{$attribute}?->copy()
            ->timezone(self::ADMIN_TIMEZONE)
            ->format('d.m.Y. H:i');
    }
}
