<?php

namespace App\Models\Notifications;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    use HasFactory;

    protected $table = 'user_notification_preferences';
    protected $fillable = [
        'user_id',
        'in_app',
        'whatsapp',
        'email',
        'push',
        'quiet_hours_enabled',
        'quiet_hours_start',
        'quiet_hours_end',
        'task_notifications',
        'project_notifications',
        'invoice_notifications',
        'message_notifications',
        'follow_up_notifications',
    ];

    protected $casts = [
        'in_app' => 'boolean',
        'whatsapp' => 'boolean',
        'email' => 'boolean',
        'push' => 'boolean',
        'quiet_hours_enabled' => 'boolean',
        'quiet_hours_start' => 'time',
        'quiet_hours_end' => 'time',
        'task_notifications' => 'boolean',
        'project_notifications' => 'boolean',
        'invoice_notifications' => 'boolean',
        'message_notifications' => 'boolean',
        'follow_up_notifications' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
