<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class GithubController extends Controller
{
    public function redirectToOAuth(): RedirectResponse
    {
        return Redirect::to(
            'https://github.com/login/oauth/authorize?client_id=' . config('integrations.github.client_id')
                . '&redirect_uri=' . config('integrations.github.redirect_uri')
                . '&scope=user,repo'
        );
    }

    public function user(Request $request): JsonResponse
    {
        $response = Http::withToken($request->user()->integrations['github'])
            ->get('https://api.github.com/user');

        return new JsonResponse($response->json(), $response->status());
    }

    public function newToken(Request $request): JsonResponse
    {
        $response = Http::acceptJson()
            ->post('https://github.com/login/oauth/access_token', [
                'client_id' => config('integrations.github.client_id'),
                'client_secret' => config('integrations.github.client_secret'),
                'redirect_uri' => env('integrations.github.redirect_uri'),
                'code' => $request->input('code'),
            ]);

        if (key_exists('error', $response->json())) {
            return new JsonResponse($response->json(), 422);
        }

        $request->user()->integrations = array_merge($request->user()->integrations, ['github' => $response->json()['access_token']]);
        $request->user()->save();

        return new JsonResponse($response->json(), $response->status());
    }

    public function revokeToken(Request $request): JsonResponse
    {
        $response = Http::acceptJson()
            ->withBasicAuth(config('integrations.github.client_id'), config('integrations.github.client_secret'))
            ->delete('https://api.github.com/applications/' . config('integrations.github.client_id') . '/grant', [
                'access_token' => $request->user()->integrations['github'] 
            ]);

        return new JsonResponse($response->json(), $response->status());
    }
}
