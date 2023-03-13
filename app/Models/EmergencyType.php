<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmergencyType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function responsers(): HasMany
    {
        return $this->hasMany(Responser::class);
    }
}
