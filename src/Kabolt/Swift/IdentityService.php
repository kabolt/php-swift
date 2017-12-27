<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class IdentityService
{
    protected $username;
    protected $password;
    protected $projectName;
    protected $authurl;
    protected $storageUrl;
    protected $client;
    protected $endpoint;
    protected $tokenExpirationDate;
    protected $token = null;

    public function __construct($options)
    {

      $this->username = $options['username'];
      $this->password = $options['password'];
      $this->authUrl = $options['authUrl'];
      $this->projectName = $options['projectName'];
      $this->storageUrl = $options['storageUrl'];
      $this->endpoint = $this->storageUrl . 'AUTH_' . $this->projectName . '/';
    }


    public function authenticate() {

      $client = new Client();
      $res = $client->request('POST', $this->authUrl . 'auth/tokens', [
        'json' => [
          'auth' => [
            'identity' => [
              'methods' => ['password'],
              'password' => [
                'user' => [
                  'name' => $this->username,
                  'password' => $this->password,
                  'project' => [
                    'name' => $this->projectName
                  ],
                  'domain' => [
                    'name' => 'Default'
                  ]
                ]
              ]
            ]
          ]
        ]
      ]);

      $this->setToken($res->getHeaderLine('X-Subject-Token'));
      $content = json_decode($res->getBody());
      $this->setTokenExpirationDate($content->token->expires_at);
      return $this->getClient();
    }

    public function getEndpoint($arg) {
      if(!$arg) return $this->endpoint;
      return $this->endpoint . $arg;
    }

    public function getClient() {
      $now = new \DateTime('now');
      $expire = new \DateTime($this->getTokenExpirationDate());
      if($this->client && $expire > $now) return $this->client;
      else return $this->authenticate();
    }

    public function getToken() {
      return $this->token;
    }

    public function setToken($token) {

      $this->token = $token;
      $this->client = new Client(['headers' => ['X-Auth-Token' => $this->token]]);
    }

    public function setTokenExpirationDate($tokenExpirationDate) {
      $this->tokenExpirationDate = $tokenExpirationDate;
    }

    public function getTokenExpirationDate() {
      return $this->tokenExpirationDate;
    }
}
