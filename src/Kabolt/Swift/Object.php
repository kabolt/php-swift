<?php

namespace Kabolt\Swift;

use GuzzleHttp\Client;

class Object
{

    public $name;
    public $containerName;
    public $content;
    public $contentType;
    public $lastModified;
    private $is;

    public function __construct($options, $identityService)
    {
      $this->name = $options['name'];
      $this->containerName = $options['containerName'];
      $this->is = $identityService;
      $this->url = $this->is->getEndpoint($this->containerName . '/' . $this->name);
    }

    public function download($queryOptions = null) {
      $client = $this->is->getClient();
      $res = $client->request('GET', $this->url, [
        'query' => $queryOptions
      ]);
      $this->setContent($res->getBody());
      $this->setContentType($res->getHeaderLine('Content-Type'));
      $this->setLastModified($res->getHeaderLine('Last-Modified'));
      return $this->getContent();
    }

    public function upload($queryOptions = null) {
        if(null === $this->getContent())
          throw new \Exception("Swift: cannot upload empty object", 1);

        $client = $this->is->getClient();
        $res = $client->request('PUT', $this->url, [
          'body' => $this->getContent(),
          'query' => $queryOptions
        ]);
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

    public function updateMetadatas($metaHeaders, $isManifest = false, $queryOptions = null) {
      $client = $this->is->getClient();
      $method = $isManifest ? 'PUT' : 'POST';
      return $client->request($method, $this->url, [
        'headers' => $metaHeaders,
        'query' => $queryOptions
      ]);
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

    public function delete($queryOptions = null) {
      $client = $this->is->getClient();
      $res = $client->request('DELETE', $this->url, [
        'query' => $queryOptions
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


    /**
     * Set the value of Last Modified
     *
     * @param mixed lastModified
     *
     * @return self
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get the value of Last Modified
     *
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

}
