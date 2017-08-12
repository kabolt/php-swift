<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Swift
{

    public function __construct($username, $password, $authUrl, $projectName)
    {

      // token

      $client = new Client();
      $res = $client->request('POST', $authUrl . '/auth/token', [
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

      echo $res;

    }


}
