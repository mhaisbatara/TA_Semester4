<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;

class User extends Model implements Authenticatable, HasApiTokensContract
{
    use AuthenticatableTrait;
    // HasApiTokens trait DIHAPUS — kita implement sendiri

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden   = ['password'];

    // Wajib ada karena implement HasApiTokensContract
    public function tokens()
    {
        return $this->morphMany(\App\Models\PersonalAccessToken::class, 'tokenable');
    }

    public function tokenCan(string $ability): bool
    {
        return $this->currentAccessToken()?->can($ability) ?? false;
    }

    public function currentAccessToken()
    {
        return $this->accessToken ?? null;
    }

    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    // Custom createToken — bebas dari Sanctum SQL builder
 public function createToken(string $name, array $abilities = ['*'])
{
    $plain = \Illuminate\Support\Str::random(40);

    $token = \App\Models\PersonalAccessToken::create([
        'name'           => $name,
        'token'          => hash('sha256', $plain),
        'abilities'      => $abilities, // array langsung
        'tokenable_id'   => (string) $this->_id,
        'tokenable_type' => static::class,
    ]);

    $result = new \stdClass();
    $result->accessToken    = $token;
    $result->plainTextToken = $token->getKey() . '|' . $plain;

    return $result;
}
public function pushPrediksi(array $record): bool
{
    return (bool) static::where('_id', $this->_id)->push('riwayat_prediksi', $record);
}
}
