<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Container
{

    protected $token;
    protected $name;
    protected $storageUrl;
    protected $finalUrl;
    protected $objects;


    public function __construct($options)
    {

      $this->storageUrl = $options['storageUrl'];
      $this->token = $options['token'];
      $this->name = $options['name'];
      $this->projectName = $options['projectName'];

      $this->finaleUrl = $this->storageUrl . 'AUTH_' . $this->projectName;

      $client = new Client();

      $res = $client->request('GET', $this->finaleUrl . '/' . $this->name . '?format=json', [
        'headers' => [
          'X-Auth-Token' => $this->token
        ]
      ]);

      $this->objects = json_decode($res->getBody());
    }

    public function getObject($name) {

      foreach ($this->objects as $object) {
        if($object->name == $name) {
          echo $object->name;
          return;
        }
      }

      echo 'object not found';
      return;

    }

}
