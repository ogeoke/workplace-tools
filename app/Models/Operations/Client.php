<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'uuid',
        'organization_id',
        'company_id',
        'name',
        'organization_name',
        'email',
        'phone',
        'address',
        'contact_person',
        'contract_status',
        'notes',
        'created_by',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(ClientFollowUp::class);
    }

    public function tracker(): BelongsTo
    {
        return $this->belongsTo(ClientTracker::class);
    }
}
