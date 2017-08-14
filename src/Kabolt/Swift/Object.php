<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Object
{

    public $name;
    public $containerName;
    public $content;
    public $contentType;
    private $is;

    public function __construct($options, $identityService)
    {
      $this->name = $options['name'];
      $this->containerName = $options['containerName'];
      $this->is = $identityService;
      $this->url = $this->is->getEndpoint($this->containerName . '/' . $this->name);
    }

    public function download() {
      $client = $this->is->getClient();
      $res = $client->request('GET', $this->url);
      $this->setContent($res->getBody());
      $this->setContentType($res->getHeaderLine('Content-Type'));
      return $this->getContent();
    }

    public function upload() {
      if(null === $this->getContent())
        throw new \Exception("Swift: cannot upload empty object", 1);

      $client = $this->is->getClient();
      $res = $client->request('PUT', $this->url, ['body' => $this->getContent()]);
    }

    public function exists() {

      $client = $this->is->getClient();
      try {
        $res = $client->request('HEAD', $this->url);
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
      $res = $client->request('DELETE', $this->url);
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
