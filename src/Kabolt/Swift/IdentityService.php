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
    }

    public function getEndpoint($arg) {
      if(!$arg) return $this->endpoint;
      return $this->endpoint . $arg;
    }

    public function getClient() {
      if($this->client) return $this->client;
      else return $this->authenticate();
    }

    public function getToken() {
      return $this->token;
    }

    public function setToken($token) {

      $this->token = $token;
      $this->client = new Client(['headers' => ['X-Auth-Token' => $this->token]]);
    }
}
