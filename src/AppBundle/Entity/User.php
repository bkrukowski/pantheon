<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name=User::TABLE_NAME,
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="email", columns={"email"})
 *     }
 * )
 */
class User implements UserInterface, \Serializable
{
    const TABLE_NAME = 'users';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $password;

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'password' => $this->password,
            'email' => $this->email,
        ]);
    }

    public function unserialize($serialized)
    {
        $unserialized = unserialize($serialized);
        foreach (['id', 'password', 'email'] as $key) {
            $this->$key = $unserialized[$key];
        }
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
