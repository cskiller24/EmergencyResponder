<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'line',
        'zip',
        'city',
        'region',
        'country',
    ];

    public function locatable(): MorphTo
    {
        return $this->morphTo();
    }
}
