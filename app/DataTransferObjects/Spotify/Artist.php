<?php

namespace App\DataTransferObjects\Spotify;

final readonly class Artist
{
    public function __construct(
        public string $id,
        public string $name,
        public int $popularity,
    ) {}
}
