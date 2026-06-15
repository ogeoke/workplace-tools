<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DieselRecord extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'organization_id',
        'site_location',
        'quantity',
        'amount',
        'record_date',
        'vendor',
        'approved_by',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'amount' => 'decimal:2',
        'record_date' => 'date',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
