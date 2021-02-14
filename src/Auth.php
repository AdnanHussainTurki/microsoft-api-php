<?php


namespace myPHPnotes\Microsoft;

use myPHPnotes\Microsoft\Handlers\Session;


class Auth  {
    protected $host = "https://login.microsoftonline.com/";
    protected $resource = "https://graph.microsoft.com/";
    protected $tenant_id;
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $scopes;
    protected $guzzle;
    protected $refreshToken;
    public function __construct(string $tenant_id, string $client_id, string $client_secret, string $redirect_uri, array $scopes = [])
    {
        $this->tenant_id = $tenant_id;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scopes = $scopes;
        Session::set("host", $this->host);
        Session::set("resource", $this->resource);
        Session::set("tenant_id", $tenant_id);
        Session::set("client_id", $client_id);
        Session::set("client_secret", $client_secret);
        Session::set("redirect_uri", $redirect_uri);
        Session::set("scopes", $scopes);
        if (!isset($_SESSION['state'])) {
            Session::set("state", random_int(1, 200000));
        }
        $this->guzzle = new \GuzzleHttp\Client();
    }
    public function setRefreshToken(string $refreshToken) 
    {
        $this->refreshToken = $refreshToken;
        Session::set("refreshToken", $this->refreshToken);
        return Session::get("refreshToken");
    }
    public function getAccessTokenUsingRefreshToken(string $refreshToken = null)
    {
        if ($refreshToken) {
            $this->setRefreshToken($refreshToken);
        }
        $url = $this->host. $this->tenant_id ."/oauth2/v2.0/token";
        $tokens = $this->guzzle->post($url, [
            'form_params' => [
                'client_id' => Session::get("client_id"),
                'client_secret' => Session::get("client_secret"),
                'grant_type' => 'refresh_token',
                'refresh_token' => Session::get("refreshToken")
            ],
        ])->getBody()->getContents();
        return json_decode($tokens)->access_token;
    }
    public function setAccessToken(string $accessToken = null)
    {
        if (!$accessToken) {
            $this->accessToken = $this->getAccessTokenUsingRefreshToken();
        } else {
            $this->accessToken = trim($accessToken);
        }
        Session::set("accessToken", $this->accessToken);
        return Session::get("accessToken");
    }
    public function getAuthUrl()
    {
        $parameters = [
            'client_id' => $this->client_id,
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'response_mode' => 'query',
            'scope' => implode(' ', $this->scopes),
            'state' => Session::get("state")
        ];
        return $this->host . $this->tenant_id . "/oauth2/v2.0/authorize?". http_build_query($parameters);
    }
    public function getToken(string $code, string $state = null)
    {
        if (!is_null($state)) {
            if (Session::get("state") != $state) {
                throw new \Exception("State parameter does not matched.", 1);
                return false;
            }
        }
        $url = $this->host. $this->tenant_id ."/oauth2/v2.0/token";
        $tokens = $this->guzzle->post($url, [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri' => $this->redirect_uri,
                'scope' => implode(' ', $this->scopes),
                'grant_type' => 'authorization_code',
                'code' => $code
            ],
        ])->getBody()->getContents();
        return json_decode($tokens);
    }
}