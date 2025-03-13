<?php

namespace App\DataTransferObjects\Spotify;

use DateTimeImmutable;

final readonly class AccessTokenDetails
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public DateTimeImmutable $expiresAt,
    ) { }
}
