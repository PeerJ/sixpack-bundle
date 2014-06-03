<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Peerj\Bundle\SixPackBundle\Classes\SixPackBase;

class BasicSixPackClient
{
    protected $securityContext;
    protected $client;
    protected $isUser;
    protected $config;

    public function __construct($config, $securityContext)
    {
        $this->config = $config;
        $this->securityContext = $securityContext;
        $this->isUser = $config['isUser'];

        if ($this->isUser) {
            $config['clientId'] = $this->getSessionClientId();
        }
    }

    public function newClient($clientId)
    {
        $newConfig = $this->config;
        $newConfig['clientId'] = $clientId;

        return new BasicSixPackClient($newConfig, $this->securityContext);
    }

    protected function getSessionClientId()
    {
        $userId = null;
        if ($this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
            $userId = $user->getId();
        }

        return $userId;
    }

    protected function getClientId()
    {
        return $this->getClient()->getClientid();
    }

    public function getClient()
    {
        if (!$this->client) {
            $this->client = new SixPackBase($this->config);
        }

        return $this->client;
    }

    public function participate($experiment, $alternatives, $trafficFraction = 1)
    {
        return $this->getClient()->participate($experiment, $alternatives, $trafficFraction);
    }

    public function convert($experiment, $kpi = null)
    {
        return $this->getClient()->convert($experiment, $kpi);
    }

    public function setCookie($response)
    {
        $this->getClient()->setCookie($response);
    }
}
