<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;

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
        $clientId = null;
        $this->isUser = $config['isUser'];
        if ($this->isUser) {
            $config['clientId'] = $this->getSessionClientId();
        }

        $this->client = new \SeatGeek\Sixpack\Session\Base($config);
    }

    public function newClient($client_id)
    {
        $new_config = $this->config;
        $new_config['clientId'] = $client_id;
        return new BasicSixPackClient($new_config, $this->securityContext);
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
        return $this->client;
    }

    public function participate($experiment, $alternatives, $traffic_fraction = 1)
    {
        return $this->getClient()->participate($experiment, $alternatives, $traffic_fraction);
    }
    
    public function convert($experiment, $kpi = null)
    {
        return $this->getClient()->convert($experiment, $kpi);
    }
}
