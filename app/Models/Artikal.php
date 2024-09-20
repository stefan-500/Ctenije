<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artikal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Soft-delete povezanu Knjigu kada je artikal soft-deleted
        static::deleting(function ($artikal) {
            if (!$artikal->isForceDeleting()) { // Provjera da li je event soft-delete
                $artikal->knjiga()->delete();
            }
        });

        // Vracanje povezane knjige nakon vracanja artikla
        static::restoring(function ($artikal) {
            $artikal->knjiga()->restore();
        });
    }

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
