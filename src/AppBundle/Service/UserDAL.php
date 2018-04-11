<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserDAL
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addUser(User $user)
    {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        } catch(Throwable $throwable) {
            return false;
        }
    }

    public function getUserByLogin($login) {
        try {
            return $this->entityManager->getRepository(User::class)->findOneByLogin($login);
        } catch (Throwable $throwable) {
            return null;
        }
    }

    public function updateUser(User $user)
    {
        try {
            $u = $this->entityManager->getRepository(User::class)->findOneByLogin($user->getLogin());
            $u->setName($user->getName());
            $u->setPassword($user->getPassword());
            $u->setPicURL($user->getPicURL());
            $u->setIsAdmin($user->getIsAdmin());
            $u->setIsActive($user->getIsActive());
            $u->setCreatedOn($user->getCreatedOn());
            $this->entityManager->flush();
            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }
}
