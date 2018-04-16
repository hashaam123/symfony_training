<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManager;

class UserService
{
    /**
     * @var EntityManager
     * @package Doctrine\ORM;
     */
    private $entityManager;

    /**
     * @var SessionManager
     * @package AppBundle\Service
     */
    private $sessionManager;

    /**
     * @var ValidatorInterface
     * @package Symfony\Component\Validator\Validator
     */
    private $validator;

    /**
     * @var LoggerInterface
     * @package Psr\Log
     */
    private $logger;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * UserService constructor.
     * @param Doctrine\ORM\EntityManager $entityManager
     * @param SessionManager $sessionManager
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(EntityManager $entityManager, SessionManager $sessionManager, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
        $this->validator = $validator;
        $this->logger = $logger;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    /**
     * @param AppBundle\Entity\User $user
     * @return bool|string
     */
    public function validateUser(User $user)
    {
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return (string)$errors;
        } else {
            return true;
        }
    }

    /**
     * @param AppBundle\Entity\User $user
     * @return bool|string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addUser(User $user)
    {
        $user->setIsActive(true);
        $user->setIsAdmin(false);
        $user->setCreatedOn(new \DateTime);
        $file = $user->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $user->setPicURL($fileName);
        $this->logger->info($this->validateUser($user));
        $status = $this->validateUser($user);
        if ($status === true) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->logger->info("User added", [$user->getLogin(), (string)$user->getCreatedOn()->format("y:M:d:h:m:s")]);
        }
        return $status;
    }

    /**
     * @param $login
     * @return mixed
     */
    public function getUserByLogin($login)
    {
        return $this->entityManager->getRepository(User::class)->findOneByLogin($login);
    }

    /**
     * @param AppBundle\Entity\User $user
     * @return bool|string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
        $status = $this->validateUser($user);
        if ($status === true) {
            $storedUser = $this->entityManager->getRepository(User::class)->findOneByLogin($user->getLogin());
            $storedUser->setName($user->getName());
            $storedUser->setPassword($user->getPassword());
            $storedUser->setPicURL($user->getPicURL());
            $storedUser->setIsAdmin($user->getIsAdmin());
            $storedUser->setIsActive($user->getIsActive());
            $storedUser->setCreatedOn($user->getCreatedOn());
            $this->entityManager->flush();
            $this->sessionManager->setUset($storedUser);
            $this->logger->info("User modified", [$user->getLogin(), (string)$user->getCreatedOn()->format("y:M:d:h:m:s")]);
        }
        return $status;
    }

    /**
     * @param AppBundle\Entity\User $user
     * @return User
     */
    public function getUser(User $user)
    {
        $newUser = new User();
        $newUser->setLogin($user->getLogin());
        $newUser->setName($user->getName());
        $newUser->setPassword($user->getPassword());
        return $newUser;
    }
}
