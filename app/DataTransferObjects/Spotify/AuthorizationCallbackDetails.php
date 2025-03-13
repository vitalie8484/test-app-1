<?php

namespace App\DataTransferObjects\Spotify;

final readonly class AuthorizationCallbackDetails
{
    public function __construct(
        public string $authorizationCode,
        public string $expectedState,
        public string $state,
        public string $codeVerifier,
    ) {}
}
