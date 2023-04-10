<?php

namespace App\Models;

use App\Enums\ResponderStatusEnum;
use App\Models\Scopes\Finder;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Responder extends Model
{
    use HasFactory;
    use HasUuids;
    use Finder;
    use Searchable;

    protected $searchable = [
        'columns' => [
            'emergency_types.name' => 20,
            'responders.name' => 50,
            'locations.city' => 30,
            'locations.country' => 10,
            'locations.city' => 10,
            'locations.region' => 10,
            'contacts.detail' => 10,
        ],
        'joins' => [
            'emergency_types' => ['emergency_types.id', 'responders.emergency_type_id'],
            'locations' => ['locations.locatable_id', 'responders.id'],
            'contacts' => ['contacts.contactable_id', 'responders.id'],
        ]
    ];

    protected $fillable = [
        'emergency_type_id',
        'name',
        'description',
        'status',
        'longitude',
        'latitude',
    ];

    protected $casts = [
        'status' => ResponderStatusEnum::class
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
