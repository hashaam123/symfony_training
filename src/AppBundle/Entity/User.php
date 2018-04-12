<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Login", type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=25)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=25)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreatedOn", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $createdOn;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsActive", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="PicURL", type="string", length=250)
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     */
    private $picURL;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsAdmin", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $isAdmin;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return User
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set picURL
     *
     * @param string $picURL
     *
     * @return User
     */
    public function setPicURL($picURL)
    {
        $this->picURL = $picURL;

        return $this;
    }

    /**
     * Get picURL
     *
     * @return string
     */
    public function getPicURL()
    {
        return $this->picURL;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
}

