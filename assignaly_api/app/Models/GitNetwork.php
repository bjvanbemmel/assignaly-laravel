<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GitNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'public_url',
        'api_url',
    ];

    public function integrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GitIntegration::class, 'network');
    }
}
