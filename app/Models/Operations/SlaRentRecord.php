<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaRentRecord extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sla_rent_records';
    protected $fillable = [
        'uuid',
        'organization_id',
        'company_id',
        'type',
        'start_date',
        'end_date',
        'renewal_date',
        'amount',
        'payment_status',
        'responsible_person_id',
        'reminder_days',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_person_id');
    }
}
