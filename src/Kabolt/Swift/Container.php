<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Container
{

    protected $token;
    public $name;
    protected $lastObjectList;
    public $objectCount;

    public function __construct($options, $identityService)
    {
      $this->name = $options['name'];
      $this->is = $identityService;
      $this->url = $this->is->getEndpoint($this->name);
    }

    public function getObject($name) {
       return new Object(['name' => $name, 'containerName' => $this->name], $this->is);
    }

    public function createObject($name, $data) {
      $object = new Object(['name' => $name, 'containerName' => $this->name], $this->is);
      $object->setContent($data);
      return $object;
    }

    public function upload() {
      $client = $this->is->getClient();
      $res = $client->request('PUT', $this->url);
    }

    public function delete() {
      $client = $this->is->getClient();
      $res = $client->request('DELETE', $this->url);
    }

    public function objectExists($objectName) {
      $object = new Object(['name' => $name, 'containerName' => $this->name], $this->is);
      return $object->exists();
    }

    public function listObjects() {
      $client = $this->is->getClient();
      $res = $client->request('GET', $this->url . '?format=json');
      $this->lastObjectList = json_decode($res->getBody());
      return $this->lastObjectList;
    }

    /**
     * Get the value of Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of Object Count
     *
     * @return mixed
     */
    public function getObjectCount()
    {
        return $this->objectCount;
    }

}
