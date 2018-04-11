<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class UserBAL
{
    private $sessionManager;
    private $userDAL;
    private $rootDir;

    public function __construct(UserDAL $userDAL, SessionManager $sessionManager)
    {
        $this->userDAL = $userDAL;
        $this->sessionManager = $sessionManager;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    public function addUser(User $user)
    {
        $user->setIsActive(true);
        $user->setIsAdmin(false);
        $user->setCreatedOn(new \DateTime);
        $file = $user->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $user->setPicURL($fileName);
        if ($this->userDAL->addUser($user)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByLogin($login)
    {
        return $this->userDAL->getUserByLogin($login);
    }

    public function updateUser(User $user)
    {
        $user->setLogin($this->sessionManager->getLogin());
        $user->setCreatedOn(new \DateTime);
        $user->setIsActive(true);
        $user->setIsAdmin($this->sessionManager->getIsAdmin());
        $file = $user->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $user->setPicURL($fileName);
        if ($this->userDAL->updateUser($user)) {
            $this->sessionManager->setUset($user);
            return true;
        } else {
            return false;
        }
    }

    public function getUser(User $user)
    {
        $u = new User();
        $u->setLogin($user->getLogin());
        $u->setName($user->getName());
        $u->setPassword($user->getPassword());
        return $u;
    }
}