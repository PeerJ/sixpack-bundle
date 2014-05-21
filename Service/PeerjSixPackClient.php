<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class PeerjSixPackClient extends SixPackClient
{
    protected $encrypter;

    public function __construct($config, $securityContext, $em, $encrypter = null)
    {
        $this->encrypter = $encrypter;
        parent::__construct($config, $securityContext, $em);
    }
    
    protected function getSessionClientId()
    {
        $clientId = null;
        $userId = parent::getSessionClientId();
        if ($userId && $this->encrypter) {
            $clientId = $this->encrypter->encryptId($userId);
        }

        return $clientId;
    }
    
    protected function getDecryptedClientId()
    {
        $clientId = parent::getClientId();
        if ($clientId && $this->encrypter) {
            $clientId = $this->encrypter->decryptId($clientId);
        }

        return $clientId;
    }
    
}
