<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string|null $provider_user_name
 * @property string|null $provider_user_email
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $token_expires_at
 * @property \Illuminate\Support\Collection|null $scopes
 * @property \Illuminate\Support\Collection|null $raw_profile
 */
class OAuthAccount extends Model
{
    use HasFactory;

    protected $table = 'oauth_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'provider_user_name',
        'provider_user_email',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'scopes',
        'raw_profile',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'scopes' => 'collection',
        'raw_profile' => 'collection',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
        'raw_profile',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
