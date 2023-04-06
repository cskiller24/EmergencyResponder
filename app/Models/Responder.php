<?php

namespace App\Models;

use App\Models\Scopes\Finder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Responder extends Model
{
    use HasFactory, HasUuids, Finder;

    protected $fillable = [
        'emergency_type_id',
        'name',
        'description',
        'status',
        'longitude',
        'latitude'
    ];

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'locatable');
    }

    public function relatedLinks(): MorphMany
    {
        return $this->morphMany(RelatedLink::class, 'related_linkable');
    }

    public function emergencyType(): BelongsTo
    {
        return $this->belongsTo(EmergencyType::class);
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
}
