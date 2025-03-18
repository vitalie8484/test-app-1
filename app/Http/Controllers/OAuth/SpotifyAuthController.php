<?php

namespace App\Http\Controllers\OAuth;

use App\Interfaces\Spotify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\DataTransferObjects\Spotify\AuthorizationCallbackDetails;
use Exception;

class SpotifyAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $redirectDetails = app(Spotify::class)->getAuthRedirectDetails();

        $request->seddion->put('oauth_spotify_state', $redirectDetails->state);
        $request->session->put('oauth_spotify_code_verifier', $redirectDetails->codeVerifier);

        return redirect($redirectDetails->authorizationUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->input('error') === 'access_denied') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Spotify account connection denied.');
        }

        $hasRequiredFields = $request->has(['code', 'state'])
            && $request->session()->has('oauth_spotify_state')
            && $request->session()->has('oauth_spotify_code_verifier');

        if (! $hasRequiredFields) {
            abort(400, 'Missing required fields.');
        }

        $calbackDetails = new AuthorizationCallbackDetails(
            authorizationCode: $request->input('code'),
            expectedState: $request->session()->pull('oauth_spotify_state'),
            state: $request->input('state'),
            codeVerifier: $request->session()->pull('oauth_spotify_code_verifier')
        );

        try {
            $accessDetails = app(Spotify::class)->authorize($calbackDetails);

            $request->user()->updateSpotifyOAuthDetails(
                accessToken: $accessDetails->accessToken,
                refreshToken: $accessDetails->refreshToken,
                expiresAt: $accessDetails->expiresAt
            );
        } catch (Exception) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Failed to connect with Spotify account.');
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Spotify account connected successfully.');
    }
}
