<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientTracker extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'client_tracker';
    protected $fillable = [
        'uuid',
        'organization_id',
        'client_id',
        'status',
        'account_manager_id',
        'activity_history',
        'last_activity_at',
        'expected_closure_date',
        'closure_notes',
        'created_by',
    ];

    protected $casts = [
        'activity_history' => 'json',
        'last_activity_at' => 'datetime',
        'expected_closure_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }
}
