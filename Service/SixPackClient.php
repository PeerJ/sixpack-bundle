<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;

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
        $sixpackUser = $repo->findOneBy(array('user' => $user, 'client_id' => $this->getClientId()));
        if (!$sixpackUser) {
            $sixpackUser = new \Peerj\Bundle\SixPackBundle\Entity\SixpackUser();
            $sixpackUser->setUser($user);
            $sixpackUser->setClientId($this->getClientId());
            $this->em->persist($sixpackUser);
            $this->em->flush();
        }
    }
    
    public function convert($experiment, $kpi = null, $convert_all = true)
    {
        // it only makes sense to convert everything if it not a user
        if ($this->isUser || !$convert_all) {
            return parent::convert($experiment, $kpi);
        }

        $repo = $this->em->getRepository('PeerjSixPackBundle:SixpackUser');
        $clients = $repo->findAllAssociatedClients($this->getClientId());
        
        foreach ($clients as $client) {
            $convert_client = $this->newClient($client->getClientId());
            $convert_client->convert($experiment, $kpi);
        }
    }
}
