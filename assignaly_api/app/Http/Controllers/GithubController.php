<?php

namespace App\Http\Controllers;

use App\Http\Requests\GithubNewRepositoryRequest;
use App\Http\Requests\GithubNewTokenRequest;
use App\Http\Requests\GithubUpdateRepositoryRequest;
use App\Models\Assignment;
use App\Models\GitIntegration;
use App\Models\GitNetwork;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
        try {
            $response = Http::withToken($request->user()->integration('Github')->api_key)
            ->get('https://api.github.com/user');

            $request->user()->integration('Github')->update([
                'username' => $response->json()['login'],
            ]);

            return new JsonResponse($response->json(), $response->status());

        } catch (\Exception $e) {

            return new JsonResponse([], 404);
        }
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

        GitIntegration::firstOrCreate([
            'network_id' => GitNetwork::query()->where('name', 'Github')->first()->id,
            'user_id' => $request->user()->id,
        ],
        [
            'network_id' => GitNetwork::query()->where('name', 'Github')->first()->id,
            'user_id' => $request->user()->id,
            'api_key' => $response->json()['access_token'],
        ])
        ->update([
            'api_key' => $response->json()['access_token'],
        ]);

        return new JsonResponse($response->json(), $response->status());
    }

    public function revokeToken(Request $request): JsonResponse
    {
        $response = Http::acceptJson()
            ->withBasicAuth(config('integrations.github.client_id'), config('integrations.github.client_secret'))
            ->delete('https://api.github.com/applications/' . config('integrations.github.client_id') . '/grant', [
                'access_token' => $request->user()->integration('Github')->api_key
            ]);

        $request->user()->integration('Github')->delete();

        return new JsonResponse($response->json(), $response->status());
    }

    public function newRepository(GithubNewRepositoryRequest $request): JsonResponse
    {
        $response = Http::acceptJson()
            ->withToken($request->user()->integration('Github')->api_key)
            ->post('https://api.github.com/user/repos', [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'private' => $request->boolean('private'),
            ]);

        $responseJson = $response->json();

        $assignment = Assignment::findOrFail($request->input('assignment_id'));
        $assignment->update([
            'remote_repository' => [
                'public_url' => $responseJson['html_url'],
                'api_url' => $responseJson['url'],
                'private' => $responseJson['private'],
            ],
            'integration_type' => $request->input('integration_type'),
        ]);

        return new JsonResponse($response->json(), $response->status());
    }

    public function updateRepository(GithubUpdateRepositoryRequest $request, Assignment $assignment): JsonResponse
    {
        $response = Http::acceptJson()
            ->withToken($request->user()->integration('Github')->api_key)
            ->patch($assignment->remote_repository['api_url'], [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'private' => $request->boolean('private'),
            ]);

        if (!$response->successful()) {
            return new JsonResponse($response->json(), $response->status());
        }

        $responseJson = $response->json();

        $assignment->update([
            'remote_repository' => [
                'public_url' => $responseJson['html_url'],
                'api_url' => $responseJson['url'],
                'private' => $responseJson['private'],
            ],
            'integration_type' => $request->input('integration_type'),
        ]);

        return new JsonResponse($responseJson, $response->status());
    }

    public function deleteRepository(Request $request, Assignment $assignment): JsonResponse
    {
        $response = Http::acceptJson()
            ->withToken($request->user()->integration('Github')->api_key)
            ->delete($assignment->remote_repository['api_url']);

        if ($response->successful()) {
            $assignment->update([
                'remote_repository' => null,
            ]);
        }

        return new JsonResponse($response->json(), $response->status());
    }

    public function addCollaboratorToRepository(Request $request, Assignment $assignment, User $user): JsonResponse
    {
        $response = Http::acceptJson()
            ->withToken($request->user()->integration('Github')->api_key)
            ->put($assignment->remote_repository['api_url']
                . '/collaborators/'
                . $user->integration('Github')->username, 
                [
                    'permission' => 'all',
                ]
            );

        return new JsonResponse($response->json(), $response->status());
    }

    public function isCollaboratorToRepository(Request $request, Assignment $assignment, User $user): JsonResponse
    {
        if (is_null($assignment->remote_repository)) {
            return new JsonResponse('No remote repository found.', 500);
        }

        $response = Http::acceptJson()
            ->withToken($request->user()->integration('Github')->api_key)
            ->get($assignment->remote_repository['api_url']
                . '/collaborators/'
                . $user->integration('Github')->username);

        return new JsonResponse($response->json(), $response->status());
    }
}
