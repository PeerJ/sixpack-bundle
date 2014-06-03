<?php

namespace Peerj\Bundle\SixPackBundle\Listener;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;

use Psr\Log\LoggerInterface;


/**
 * Class LoginListener
 *
 * @package Peerj\Bundle\SixPackBundle\Listener
 */
class LoginListener implements EventSubscriberInterface
{
    /** @var SecurityContextInterface  */
    private $securityContext;

    /** @var LoggerInterface */
    protected $logger;

    protected $client;

    /**
     * @param SecurityContextInterface $securityContext
     * @param LoggerInterface          $logger
     * @param                          $client
     */
    public function __construct(SecurityContextInterface $securityContext, LoggerInterface $logger, $client)
    {
        $this->securityContext = $securityContext;
        $this->logger = $logger;
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        );
    }

    /**
     * @param UserEvent $event
     */
    public function onImplicitLogin(UserEvent $event)
    {
        $this->logger->debug("onImplicitLogin");
        $this->registerUserMapping($event->getUser());
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->logger->debug("onSecurityInteractiveLogin");
        $this->registerUserMapping($event->getAuthenticationToken()->getUser());
    }

    /**
     * @param $user
     */
    public function registerUserMapping($user)
    {
        try {
            $this->client->registerUser($user);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }


}
