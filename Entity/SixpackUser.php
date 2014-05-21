<?php

namespace Peerj\Bundle\SixPackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
     */
    public function __construct()
    {
        //parent::__construct();
    }
    
    /**
     * User the URL belongs to
     *
     * @ORM\ManyToOne(targetEntity="peerj\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * @var User
     */
    protected $user;

    
    /**
     * @var string $client_id
     *
     * @ORM\Column(name="client_id", type="string", length=80, nullable=false)
     */
    protected $client_id;
    
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
    
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }
    
    public function getClientId()
    {
        return $this->client_id;
    }
}
