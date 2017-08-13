<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Object
{

    public $name;
    public $content;
    public $contentType;
    protected $container;

    public function __construct($name, $container)
    {

      $this->container = $container;
      $this->setName($name);
    }


    public function download() {
      $client = new Client();
      $res = $client->request('GET', $this->container->getFinaleUrl() . '/' . $this->container->getName() . '/' . $this->name, [
        'headers' => [
          'X-Auth-Token' => $this->container->getToken()
        ]
      ]);

      $this->setContent($res->getBody());
      $this->setContentType($res->getHeaderLine('Content-Type'));

      return $this->getContent();
    }

    public function upload() {
      if(null === $this->getContent())
        throw new \Exception("Swift: cannot upload empty object", 1);

      $client = new Client();
      $res = $client->request('PUT', $this->container->getFinaleUrl() . '/' . $this->container->getName() . '/' . $this->name, [
        'headers' => [
          'X-Auth-Token' => $this->container->getToken()
        ],
        'body' => $this->getContent()
      ]);
    }

    public function exists() {
      $client = new Client();

      try {

        $res = $client->request('HEAD', $this->container->getFinaleUrl() . '/' . $this->container->getName() . '/' . $this->name, [
          'headers' => [
            'X-Auth-Token' => $this->container->getToken()
          ],
          'body' => $this->getContent()
        ]);

        return true;

      } catch (\GuzzleHttp\Exception\ClientException $e) {
          if(404 == $e->getCode()) return false;
      }


    }

    /**
     * Set the value of Content
     *
     * @param mixed content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function delete() {
      $client = new Client();
      $res = $client->request('DELETE', $this->container->getFinaleUrl() . '/' . $this->container->getName() . '/' . $this->name, [
        'headers' => [
          'X-Auth-Token' => $this->container->getToken()
        ]
      ]);
    }


    public function setName($name) {
      $this->name = $name;
      return $this;
    }

    public function getName() {
      return $this->name;
    }

    /**
     * Get the value of Content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of Content Type
     *
     * @param mixed contentType
     *
     * @return self
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get the value of Content Type
     *
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

}
