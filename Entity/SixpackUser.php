<?php

namespace Peerj\Bundle\SixPackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Peerj\Bundle\SixPackBundle\Model\SixPackUserInterface;

/**
 * Peerj\Bundle\SixPackBundle\Entity\SixpackUser
 *
 * @ORM\Table(name="sixpack_user")
 * @ORM\Entity(repositoryClass="Peerj\Bundle\SixPackBundle\Entity\SixpackUserRepository")
 */
class SixpackUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * User the URL belongs to
     *
     * @ORM\ManyToOne(targetEntity="Peerj\Bundle\SixPackBundle\Model\SixPackUserInterface")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * @var User
     */
    protected $user;

    /**
     * @var string $clientId
     *
     * @ORM\Column(name="client_id", type="string", length=80, nullable=false)
     */
    protected $clientId;

    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientId()
    {
        return $this->clientId;
    }
}
