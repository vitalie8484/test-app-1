<?php

namespace App\Interfaces;

use App\Collections\Spotify\ArtistCollection;
use App\DataTransferObjects\Spotify\AccessTokenDetails;
use App\DataTransferObjects\Spotify\AuthorizationCallbackDetails;
use App\DataTransferObjects\Spotify\AuthorizationRedirectDetails;
use App\Models\User;

interface Spotify
{
    public function getAuthRedirectDetails(): AuthorizationRedirectDetails;

    public function authorize(
        AuthorizationCallbackDetails $callbackDetails
    ): AccessTokenDetails;

    public function getTopArtists(User $user): ArtistCollection;
}
