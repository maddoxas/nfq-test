<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="admins")
 * @ORM\Entity
 * @UniqueEntity("username")
 */
class Admin implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id     
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $password;


    /**
     * Returns the salt that was originally used to encode the password.
     * Returns null if no salt was used to enocde.
     *
     * @return string|null Security salt
     */

    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     * 
     * @return string Password
     */

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the roles granted to the user.
     * 
     * @return array Roles
     */

    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Serializes User to store in a session.
     * 
     * @return string Serialized user
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username
        ]);
    }

    /**
     * Unserializes a user.
     *
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username
        ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Admin
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
