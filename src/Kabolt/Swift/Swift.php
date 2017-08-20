<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Swift
{

    protected $identityService;

    public function __construct($identityService)
    {
      $this->identityService = $identityService;
    }
    
    public function createContainer($name) {
      return new Container(['name' => $name], $this->identityService);
    }

    public function getContainer($name) {
      $container = new Container(['name' => $name], $this->identityService);
      return $container->exists() ? $container : null;
    }

    public function getObject($name, $containerName) {
      $object = new Object(['name' => $name , 'containerName' => $containerName], $this->identityService);
      return $object->exists() ? $object : null;
    }

    public function createObject($name, $containerName) {
      return new Object(['name' => $name , 'containerName' => $containerName], $this->identityService);
    }

    public function getObjectPublicUrl($name, $containerName) {
        return $this->identityService->getEndpoint($containerName . '/' . $name);
    }


}
