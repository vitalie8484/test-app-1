<?php

namespace App\Services\Spotify;

use App\Models\User;
use App\Interfaces\Spotify;
use Illuminate\Support\Str;
use App\Collections\Spotify\ArtistCollection;
use Saloon\Http\OAuth2\GetAccessTokenRequest;
use App\Http\Integrations\Spotify\SpotifyConnector;
use App\DataTransferObjects\Spotify\AccessTokenDetails;
use App\DataTransferObjects\Spotify\AuthorizationCallbackDetails;
use App\DataTransferObjects\Spotify\AuthorizationRedirectDetails;

class SpotifyService implements Spotify
{
    private function connector(): SpotifyConnector
    {
        return new SpotifyConnector();
    }

	public function getAuthRedirectDetails(): AuthorizationRedirectDetails
	{
		$codeVerifier = Str::random(random_int(43, 128));
        $codeChallenge = hash('sha256', $codeVerifier, true);
        $codeChallenge = strtr(base64_encode($codeChallenge), '+/', '-_');
        $codeChallenge = trim($codeChallenge, '=');

        $connector = $this->connector();

        $authorizationUrl = $connector->getAuthorizationUrl([
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return new AuthorizationRedirectDetails(
            authorizationUrl: $authorizationUrl,
            state: $connector->getState(),
            codeVerifier: $codeVerifier
        );
	}

	public function authorize(
        AuthorizationCallbackDetails $callbackDetails
    ): AccessTokenDetails
	{
		$tokenDetails = $this->connector()->getAccessToken(
            code: $callbackDetails->authorizationCode,
            state: $callbackDetails->state,
            expectedState: $callbackDetails->expectedState,
            requestModifier: function (GetAccessTokenRequest $saloonRequestBody) use ($callbackDetails) {
                $saloonRequestBody->body()->add(
                    'code_verifier',
                    $callbackDetails->codeVerifier
                );
            }
        );

        return new AccessTokenDetails(
            accessToken: $tokenDetails['accessToken'],
            refreshToken: $tokenDetails['refreshToken'],
            expiresAt: $tokenDetails['expiresAt'],
        );
	}

	public function getTopArtists(User $user): ArtistCollection
	{
		// Implement the method logic here
	}
}
