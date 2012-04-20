<?php

namespace Alchemy\Sdk;

use Alchemy\Sdk\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class Client extends \PhraseanetClientApi
{

  protected $instanceUri;
  protected $clientId;
  protected $clientSecret;
  protected $callbackUri;
  protected $apiVersion;
  protected $devToken;

  public function __construct($configFile, \CurlWrapper $curl)
  {
    $content = file_get_contents($configFile);
    $config = new ParameterBag(json_decode($content, true));
    try
    {
      $this->instanceUri = $config->get('instance_uri');
      $this->clientId = $config->get('client_id');
      $this->clientSecret = $config->get('client_secret');
      $this->callbackUri = $config->get('callback_uri');
      $this->apiVersion = $config->get('api_version');
      $this->devToken = $config->get('dev_token');
    }
    catch (ParameterNotFoundException $e)
    {
      throw new Exception\RuntimeException(sprintf(
                      'Bad configuration missing key %s', $e->getKey()
              ),
              0,
              $e
      );
    }

    parent::__construct(
            $this->instanceUri
            , $this->clientId
            , $this->clientSecret
            , $curl
    );
  }

  public function getAccessToken()
  {
    return $this->devToken;
  }

  public function setAccessToken($token)
  {
    
  }

  public function getInstanceUri()
  {
    return $this->instanceUri;
  }

  public function getClientId()
  {
    return $this->clientId;
  }

  public function getClientSecret()
  {
    return $this->clientSecret;
  }

  public function getCallbackUri()
  {
    return $this->callbackUri;
  }

  public function getApiVersion()
  {
    return $this->apiVersion;
  }

}

