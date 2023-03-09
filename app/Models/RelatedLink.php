<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RelatedLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
    ];

    public function related_linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
