<?php

namespace App\Models;

use App\Enums\AssignmentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'due_at',
        'finished_at',
        'review',
        'feedback',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assignment' => AssignmentStatusEnum::class,
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'assignment_users');
    }

    public function owner(): \Illuminate\database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
