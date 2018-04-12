<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserBAL
{
    private $sessionManager;
    private $userDAL;
    private $validator;
    private $rootDir;

    public function __construct(UserDAL $userDAL, SessionManager $sessionManager, ValidatorInterface $validator)
    {
        $this->userDAL = $userDAL;
        $this->sessionManager = $sessionManager;
        $this->validator = $validator;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    public function validateUser(User $user)
    {
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return false;
        } else {
            return true;
        }
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
        $status = $this->validateUser($user) && $this->userDAL->addUser($user);
        if ($status == true) {
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
        $status = $this->validateUser($user) && $this->userDAL->updateUser($user);
        if ($status == true) {
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