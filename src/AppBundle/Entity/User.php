<?php

namespace AppBundle\Entity;

class User
{
    private $userId;
    private $login;
    private $name;
    private $password;
    private $createdOn;
    private $isActive;
    private $picURL;
    private $isAdmin;

    public function getUserId()
    {
        return $this->userId;
    }

    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setPicURL(string $picURL)
    {
        $this->picURL = $picURL;
    }

    public function getPicURL()
    {
        return $this->picURL;
    }

    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
}
