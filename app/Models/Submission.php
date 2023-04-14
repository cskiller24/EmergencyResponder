<?php

namespace App\Models;

use App\Enums\SubmissionStatusEnum;
use App\Models\Scopes\Finder;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Submission extends Model
{
    use HasFactory;
    use HasUuids;
    use Searchable;
    use Finder;

    protected $fillable = [
        'submitted_by',
        'submitter_notify',
        'monitored_by',
        'emergency_type_id',
        'status',
        'name',
        'description',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status' => SubmissionStatusEnum::class,
    ];

    protected array $searchable = [
        'columns' => [
            'emergency_types.name' => 20,
            'users.email' => 50,
            'submissions.name' => 50,
            'submissions.description' => 20,
            'locations.country' => 10,
            'locations.city' => 10,
            'locations.region' => 10,
            'contacts.detail' => 10,
        ],
        'joins' => [
            'emergency_types' => ['emergency_types.id', 'submissions.emergency_type_id'],
            'locations' => ['locations.locatable_id', 'submissions.id'],
            'contacts' => ['contacts.contactable_id', 'submissions.id'],
            'users' => ['users.id', 'submissions.submitted_by'],
            'users' => ['users.id', 'submissions.monitored_by'],
        ],
    ];

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    public function monitoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitored_by', 'id');
    }

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

    public function isAuthOwner(): bool
    {
        return $this->submitted_by === auth()->id();
    }

    public function isAuthMaintainer(): bool
    {
        return $this->monitored_by === auth()->id();
    }

    public function hasMaintainer(): bool
    {
        return $this->monitored_by !== null;
    }

    public function hasNoMaintainer(): bool
    {
        return $this->monitored_by === null;
    }

    public function canEdit(): bool
    {
        return $this->isAuthOwner() && ($this->status->value !== SubmissionStatusEnum::APPROVED->value);
    }
}
