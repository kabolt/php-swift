<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Swift
{
    protected $username;
    protected $password;
    protected $projectName;
    protected $authurl;
    protected $storageUrl;

    protected $token;

    public function __construct($options)
    {

      $this->username = $options['username'];
      $this->password = $options['password'];
      $this->authUrl = $options['authUrl'];
      $this->projectName = $options['projectName'];
      $this->storageUrl = $options['storageUrl'];
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

      $this->token = $res->getHeaderLine('X-Subject-Token');
      //$tokenObject = json_decode($res->getBody());
      //echo $token;
    }

    public function getToken() {
      return $this->token;
    }

    public function setToken($token) {

      // check token validity here
      // If token is good
      $this->token = $token;
    }


    public function getContainer($name) {

      $options = [
        'token' => $this->token,
        'storageUrl' => $this->storageUrl,
        'projectName' => $this->projectName,
        'name' => $name
      ];
      return new Container($options);
    }


}
