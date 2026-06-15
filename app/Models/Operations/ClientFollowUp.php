<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientFollowUp extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'client_follow_ups';
    protected $fillable = [
        'uuid',
        'organization_id',
        'client_id',
        'follow_up_date',
        'assigned_to',
        'communication_channel',
        'status',
        'next_action',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
