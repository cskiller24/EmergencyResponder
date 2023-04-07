<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use HasRoles;
    use Searchable;

    protected array $searchable = [
        'columns' => [
            'users.email' => 15,
            'users.name' => 10,
            'roles.name' => 20,
        ],
        'joins' => [
            'model_has_roles' => ['model_has_roles.model_id', 'users.id'],
            'roles' => ['roles.id', 'model_has_roles.role_id'],
        ],
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function monitors(): HasMany
    {
        return $this->hasMany(Submission::class, 'monitored_by');
    }
}
