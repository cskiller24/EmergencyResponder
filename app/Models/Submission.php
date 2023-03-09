<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Submission extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'submitter_email',
        'submitter_notify',
        'monitored_by',
        'emergency_type_id',
        'status',
        'name',
        'description',
    ];

    public function monitoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitored_by', 'id');
    }

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
