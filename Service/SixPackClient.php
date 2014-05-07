<?php

namespace Peerj\Bundle\SixPackBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class SixPackClient
{
    protected $client;

    public function __construct($baseUrl, $cookiePrefix, $timeout)
    {
        $this->client = new \SeatGeek\Sixpack\Session\Base(array('baseUrl' => $baseUrl, 'cookiePrefix' => $cookiePrefix, 'timeout' => $timeout));
    }

    public function getClient()
    {
        return $this->client;
    }
}
