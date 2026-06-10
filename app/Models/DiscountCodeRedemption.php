<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeRedemption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(trim((string) $value));
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function porudzbina()
    {
        return $this->belongsTo(Porudzbina::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
