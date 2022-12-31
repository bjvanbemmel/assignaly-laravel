<?php

namespace App\Http\Controllers;

use App\Http\Requests\GithubDeleteRepositoryRequest;
use App\Http\Requests\GithubNewRepositoryRequest;
use App\Http\Requests\GithubNewTokenRequest;
use App\Models\Assignment;
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
                . '&scope=user,repo,delete_repo'
        );
    }

    public function user(Request $request): JsonResponse
    {
        $response = Http::withToken($request->user()->integrations['github'])
            ->get('https://api.github.com/user');

        return new JsonResponse($response->json(), $response->status());
    }

    public function newToken(GithubNewTokenRequest $request): JsonResponse
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

    public function newRepository(GithubNewRepositoryRequest $request): JsonResponse
    {
        $response = Http::acceptJson()
            ->withToken($request->user()->integrations['github'])
            ->post('https://api.github.com/user/repos', [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'private' => $request->boolean('private'),
            ]);

        $assignment = Assignment::findOrFail($request->input('assignment_id'));
        $assignment->update([
            'remote_repository' => [
                'public_url' => $response->json()['html_url'],
                'api_url' => $response->json()['url'],
            ],
            'integration_type' => $request->input('integration_type'),
        ]);

        return new JsonResponse($response->json(), $response->status());
    }

    public function deleteRepository(GithubDeleteRepositoryRequest $request): JsonResponse
    {
        $assignment = Assignment::findOrFail($request->input('assignment_id'));

        $response = Http::acceptJson()
            ->withToken($request->user()->integrations['github'])
            ->delete($assignment->remote_repository['api_url']);

        if ($response->successful()) {
            $assignment->update([
                'remote_repository' => null,
            ]);
        }

        return new JsonResponse($response->json(), $response->status());
    }
}
