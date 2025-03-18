<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'workos_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'workos_id',
        'remember_token',
        'spotify_access_token',
        'spotify_refresh_token',
        'spotify_expires_at',
    ];

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'spotify_access_token' => 'encrypted',
            'spotify_refresh_token' => 'encrypted',
            'spotify_expires_at' => 'datetime',
        ];
    }

    public function updateSpotifyOAuthDetails(
        string $accessToken,
        string $refreshToken,
        \DateTimeImmutable $expiresAt
    ): void {
        $this->spotify_access_token = $accessToken;
        $this->spotify_refresh_token = $refreshToken;
        $this->spotify_expires_at = $expiresAt;

        $this->save();
    }

    public function isConnectedToSpotify(): bool
    {
        return $this->spotify_access_token
            && $this->spotify_refresh_token
            && $this->spotify_expires_at;
    }
}
