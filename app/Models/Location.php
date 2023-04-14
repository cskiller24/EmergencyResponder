<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function fullAddress(): Attribute
    {
        return Attribute::make(
           get: fn() => sprintf(
                '%s, %s %s, %s %s',
                $this->line,
                $this->city,
                $this->region,
                $this->country,
                $this->zip
            )
        );
    }
}
