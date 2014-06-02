<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Peerj\Bundle\SixPackBundle\Entity\SixPackUser;

class SixPackClient extends BasicSixPackClient
{
    protected $em;

    public function __construct($config, $securityContext, $em)
    {
        $this->em = $em;
        parent::__construct($config, $securityContext);
    }

    public function registerUser($user)
    {
        // we should only be storing the mappings between the anon client id and the user
        if ($this->isUser) {
            return;
        }

        $repo = $this->em->getRepository('PeerjSixPackBundle:SixpackUser');
        $sixpackUser = $repo->findOneBy(array('user' => $user, 'clientId' => $this->getClientId()));
        if (!$sixpackUser) {
            $sixpackUser = new SixpackUser();
            $sixpackUser->setUser($user);
            $sixpackUser->setClientId($this->getClientId());
            $this->em->persist($sixpackUser);
            $this->em->flush();
        }
    }

    public function convert($experiment, $kpi = null, $convertAll = true)
    {
        // it only makes sense to convert everything if it not a user
        if ($this->isUser || !$convertAll) {
            return parent::convert($experiment, $kpi);
        }

        $repo = $this->em->getRepository('PeerjSixPackBundle:SixpackUser');
        $clients = $repo->findAllAssociatedClients($this->getClientId());
        if (!$client) {
            // if no associated clients clients, just convert current client
            return parent::convert($experiment, $kpi);
        } else {
            foreach ($clients as $client) {
                $convertClient = $this->newClient($client->getClientId());
                $convertClient->convert($experiment, $kpi);
            }
        }
    }
}
