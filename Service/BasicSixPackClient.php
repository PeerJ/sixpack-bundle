<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Peerj\Bundle\SixPackBundle\Classes\SixPackBase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BasicSixPackClient
{
    protected $tokenStorage;
    protected $client;
    protected $isUser;
    protected $config;

    public function __construct($config, TokenStorageInterface $tokenStorage)
    {
        $this->config = $config;
        $this->tokenStorage = $tokenStorage;
        $this->isUser = $config['isUser'];

        if ($this->isUser) {
            $config['clientId'] = $this->getSessionClientId();
        }
    }

    public function newClient($clientId)
    {
        $newConfig = $this->config;
        $newConfig['clientId'] = $clientId;

        return new BasicSixPackClient($newConfig, $this->tokenStorage);
    }

    protected function getSessionClientId()
    {
        $userId = null;
        if ($token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();
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
