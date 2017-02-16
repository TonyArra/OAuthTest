<?php

namespace App\Http\Controllers;

use App\Exceptions\GitHubOAuthException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\View\View;

class GithubController extends Controller
{

    const CLIENT_ID = 'e5ac53a19c7ff908b67f';
    const CLIENT_SECRET = '72cc3d1d57ae11681879557c502c9755fa7473a4';

    /**
     * Initiate OAuth authentication for GitHub
     *
     * @return RedirectResponse
     */
    public function authorizeUser() : RedirectResponse
    {
        $data = [
            'client_id' => self::CLIENT_ID,
            'state' => csrf_token(),
            'scope' => 'repo'
        ];

        return redirect()->secure('https://github.com/login/oauth/authorize?' . http_build_query($data));
    }

    /**
     * View GitHub user repositories and issues
     *
     * @param Request $request
     * @return View
     */
    public function viewProfile(Request $request)
    {
        try {
            $this->verifyCsrfToken($request->input('state'));
            $accessToken = $this->getAccessToken($request->input('code'));
            $client = $this->apiClient($accessToken);

            return view('profile', [
                'repos' => $this->getRepos($client),
                'issues' => $this->getIssues($client),
            ]);
        } catch (GitHubOAuthException $e) {
            return redirect('/');
        }
    }

    protected function getRepos(Client $client) : array
    {
        return json_decode($client->get('user/repos')->getBody()->getContents());
    }

    protected function getIssues(Client $client) : array
    {
        return json_decode($client->get('/issues')->getBody()->getContents());
    }

    protected function apiClient($accessToken) : Client
    {
        $client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => "token $accessToken",
            ]
        ]);

        return $client;
    }

    protected function getAccessToken($code) : string
    {
        $client = new Client([
            'base_uri' => 'https://github.com/login/oauth/'
        ]);

        $response = $client->post('access_token', [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'code' => $code,
                'state' => self::CLIENT_SECRET,
            ]
        ]);

        $body = json_decode($response->getBody());

        if (isset($body->error)) {
            throw new GitHubOAuthException($body->error_description);
        }

        return $body->access_token;
    }

    protected function verifyCsrfToken($token)
    {
        if ($token !== csrf_token()) {
            abort(403, 'Unauthorized action');
        }
    }

}
