<?php

namespace App\DataTransferObjects\Spotify;

final readonly class AuthorizationRedirectDetails
{
    public function __construct(
        public string $authorizationUrl,
        public string $state,
        public string $codeVerifier,
    ) {}
}
