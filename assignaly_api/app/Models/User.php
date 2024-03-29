<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'institute_id',
        'role_id',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'integrations',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'array',
    ];


    public function institute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function ownedAssignments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Assignment::class, 'owner_id');
    }

    public function assignments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'assignment_users');
    }

    public function allAssignments()
    {
        return Assignment::query()->select('assignments.*')
            ->distinct()
            ->leftJoin('assignment_users', function(\Illuminate\Database\Query\Builder $join) {
                $join->on('assignments.id', 'assignment_users.assignment_id')
                    ->where('assignment_users.user_id', $this->id);
        })
        ->where('owner_id', $this->id)
        ->orWhere('assignment_users.user_id', $this->id);
    }

    public function integrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GitIntegration::class);
    }

    public function integration(string $networkName)
    {
        return $this->hasMany(GitIntegration::class)->where('network_id', GitNetwork::query()->where('name', $networkName)->first()->id)->first();
    }
}
