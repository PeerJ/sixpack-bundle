<?php

namespace Peerj\Bundle\SixPackBundle\Classes;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class SixPackBase extends \SeatGeek\Sixpack\Session\Base
{
    protected $cookie;
    protected $usePhpCookie;

    public function __construct($options = array())
    {
        if (isset($options["usePhpCookie"])) {
            $this->usePhpCookie = $options["usePhpCookie"];
        }
        parent::__construct($options);
    }

    protected function storeClientId($clientId)
    {
        if ($this->usePhpCookie) {
            parent::storeClientId($clientId);
        } else {
            $cookieName = $this->cookiePrefix . '_client_id';
            $cookieExpire = time() + (60 * 60 * 24 * 30 * 100);
            $this->cookie = new Cookie($cookieName, $clientId, $cookieExpire, "/");
        }
    }

    public function setCookie($response)
    {
        if ($this->cookie) {
            $response->headers->setCookie($this->cookie);
        }
    }
}
