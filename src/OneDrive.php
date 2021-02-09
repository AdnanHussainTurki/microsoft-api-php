<?php


namespace myPHPnotes\OneDrive;


class OneDrive  {
    protected $host = "https://login.microsoftonline.com/";
    protected $resource = "https://graph.microsoft.com/";
    protected $tenant_id;
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $scopes;
    protected $guzzle;
    public function __construct(string $tenant_id, string $client_id, string $client_secret, string $redirect_uri, array $scopes)
    {
        $this->tenant_id = $tenant_id;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scopes = $scopes;
        if (!isset($_SESSION['state'])) {
            $_SESSION['state'] = random_int(1, 200000);
        }
        $this->guzzle = new \GuzzleHttp\Client();
    }
    public function getAuthUrl()
    {
        $parameters = [
            'client_id' => $this->client_id,
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'response_mode' => 'query',
            'scope' => implode(' ', $this->scopes),
            'state' => $_SESSION['state']
        ];
        return $this->host.$this->tenant_id."/oauth2/v2.0/authorize?". implode($parameters);
    }
    public function getToken(string $code, string $state = null)
    {
        if (!is_null($state)) {
            if ($_SESSION['state'] != $state) {
                throw new \Exception("State parameter does not matched.", 1);
                return false;
            }
        }
        $tokens = $guzzle->post($url, [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'resource' => $this->resource,
                'scope' => implode(' ', $this->scopes),
                'grant_type' => 'authorization_code',
                'code' => $code
            ],
        ])->getBody()->getContents();
        return json_decode($tokens);
    }
}