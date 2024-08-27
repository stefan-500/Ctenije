<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function knjiga()
    {
        return $this->hasOne(Knjiga::class);
    }

    public function artikalSlike()
    {
        return $this->hasMany(ArtikalSlika::class);
    }

    public function vrsteArtikla()
    {
        return $this->belongsToMany(VrstaArtikala::class);
    }

    public function stavkePorudzbine()
    {
        return $this->hasMany(StavkaPorudzbine::class);
    }
}
