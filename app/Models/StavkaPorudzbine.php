<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StavkaPorudzbine extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function artikal()
    {
        return $this->belongsTo(Artikal::class);
    }

    public function porudzbina()
    {
        return $this->belongsTo(Porudzbina::class);
    }
}
