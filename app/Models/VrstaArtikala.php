<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VrstaArtikala extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function artikli()
    {
        return $this->belongsToMany(Artikal::class);
    }
}
