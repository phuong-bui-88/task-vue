<?php

namespace App\Models;

use App\Mail\ResetPasswordEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
//use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'socialite_id',
        'socialite_token',
        'socialite_refresh_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendPasswordResetPassword($token)
    {
        \Mail::to($this->email)->queue(new ResetPasswordEmail($token));
    }

    public static function findOrCreateGoogleAuth($googleAuth) {
        $user = self::where('email', $googleAuth->email)->first();

        if ($user) {
            return $user;
        }

        return self::create([
            'email' => $googleAuth->email,
            'name' => $googleAuth->name,
            'password' => bcrypt(Str::random(32)),
            'socialite_id' => $googleAuth->id,
            'socialite_token' => $googleAuth->token,
            'socialite_refresh_token' => $googleAuth->refreshToken,
        ]);
    }
}
