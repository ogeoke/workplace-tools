<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteLoginVault extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'site_login_vault';
    protected $fillable = [
        'uuid',
        'organization_id',
        'site_name',
        'url',
        'username',
        'password_encrypted',
        'access_level',
        'owner_id',
        'notes',
        'created_by',
    ];

    protected $hidden = [
        'password_encrypted',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
