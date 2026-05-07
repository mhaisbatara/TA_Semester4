<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\Contracts\HasAbilities;

class PersonalAccessToken extends Model implements HasAbilities
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name', 'token', 'abilities',
        'tokenable_id', 'tokenable_type',
        'last_used_at', 'expires_at',
    ];

    protected $casts = [
        'abilities'    => 'array',   // ← fix agar tersimpan sebagai array, bukan string
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function can($ability): bool
    {
        $abs = is_string($this->abilities)
            ? json_decode($this->abilities, true)
            : ($this->abilities ?? []);

        return in_array('*', $abs) || in_array($ability, $abs);
    }

    public function cant($ability): bool
    {
        return !$this->can($ability);
    }

    public function tokenable()
    {
        return $this->morphTo('tokenable', 'tokenable_type', 'tokenable_id');
    }

    public static function findToken($token)
    {
        if (!str_contains($token, '|')) return null;

        [$id, $plain] = explode('|', $token, 2);

        return static::where('_id', $id)
            ->where('token', hash('sha256', $plain))
            ->first();
    }
}
