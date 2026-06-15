<?php

namespace App\Models\Projects;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'uuid',
        'organization_id',
        'team_id',
        'name',
        'key',
        'description',
        'status',
        'type',
        'visibility',
        'lead_id',
        'start_date',
        'end_date',
        'color',
        'settings',
        'created_by',
    ];

    protected $casts = [
        'settings' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }
}
