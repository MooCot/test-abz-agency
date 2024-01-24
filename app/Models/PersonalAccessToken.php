<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'tokenable_id',
        'tokenable_type'
    ];

    public static function expiresToken($tokenString)
    {
        $token = self::where('token', $tokenString)->first();
        $now = Carbon::now();
        $expirationMinutes = config('sanctum.expiration');
        $elapsedMinutes = $now->diffInMinutes(Carbon::parse($token->created_at));

        if ($elapsedMinutes >= $expirationMinutes || empty($token)) {
            return true;
        }
        // $token->delete();
        return false;
    }
}
