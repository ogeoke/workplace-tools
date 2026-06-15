<?php

namespace App\Models\Communication;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectMessage extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'direct_messages';
    protected $fillable = [
        'uuid',
        'from_user_id',
        'to_user_id',
        'content',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
