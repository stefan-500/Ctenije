<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestDeliveryData extends Model
{
    use HasFactory;

    protected $table = 'guest_delivery_datas';

    protected $guarded = [];

    public function porudzbina()
    {
        return $this->hasOne(Porudzbina::class);
    }
}
