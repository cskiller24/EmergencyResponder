<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Responder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'emergency_type_id',
        'location_id',
        'related_link_id',
        'name',
        'description',
        'status',
    ];

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'locatable');
    }

    public function relatedLink(): MorphOne
    {
        return $this->morphOne(RelatedLink::class, 'related_linkable');
    }

    public function emergencyType(): BelongsTo
    {
        return $this->belongsTo(EmergencyType::class);
    }
}
