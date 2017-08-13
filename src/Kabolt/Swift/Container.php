<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Container
{

    protected $token;
    public $name;
    protected $storageUrl;
    protected $finaleUrl;
    protected $lastObjectList;
    public $objectCount;

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

      $this->lastObjectList = json_decode($res->getBody());
      $this->objectCount = $res->getHeaderLine('X-Container-Object-Count');
    }

    public function getObject($name) {
       return new Object($name, $this);
    }

    public function createObject($name, $data) {
      $object = new Object($name, $this);
      $object->setContent($data);
      return $object;
    }

    public function delete() {
      $client = new Client();
      $res = $client->request('DELETE', $this->finaleUrl . '/' . $this->name, [
        'headers' => [
          'X-Auth-Token' => $this->token
        ]
      ]);
    }

    public function objectExists($objectName) {
      $object = new Object($objectName, $this);
      return $object->exists();
    }

    public function listObjects() {
      $client = new Client();

      $res = $client->request('GET', $this->finaleUrl . '/' . $this->name . '?format=json', [
        'headers' => [
          'X-Auth-Token' => $this->token
        ]
      ]);

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

    public function getToken()
    {
      return $this->token;
    }

    /**
     * Get the value of Storage Url
     *
     * @return mixed
     */
    public function getStorageUrl()
    {
        return $this->storageUrl;
    }

    /**
     * Get the value of Final Url
     *
     * @return mixed
     */
    public function getFinaleUrl()
    {
        return $this->finaleUrl;
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
