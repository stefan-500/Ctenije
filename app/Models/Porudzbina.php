<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porudzbina extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function stavkePorudzbine()
    {
        return $this->hasMany(StavkaPorudzbine::class);
    }

    public function guestDeliveryData()
    {
        return $this->belongsTo(GuestDeliveryData::class);
    }
}
