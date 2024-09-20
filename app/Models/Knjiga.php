<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Knjiga extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'isbn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function artikal()
    {
        return $this->belongsTo(Artikal::class);
    }
}
// return $this->belongsTo(Artikal::class)->withTrashed();
