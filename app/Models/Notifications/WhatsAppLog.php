<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'whatsapp_logs';
    protected $fillable = [
        'uuid',
        'organization_id',
        'user_id',
        'phone_number',
        'message',
        'delivery_status',
        'error_message',
        'green_api_message_id',
        'retry_count',
        'last_retry_at',
        'sent_at',
        'delivered_at',
        'read_at',
    ];

    protected $casts = [
        'last_retry_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];
}
