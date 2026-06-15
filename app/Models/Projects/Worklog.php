<?php

namespace App\Models\Projects;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worklog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'task_id',
        'user_id',
        'hours_spent',
        'description',
        'work_date',
        'created_by',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
