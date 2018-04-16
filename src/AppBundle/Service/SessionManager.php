<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

/**
 * Class SessionManager
 * @package AppBundle\Service
 */
class SessionManager
{
    /**
     * @var string
     */
    const id = "id";

    /**
     * @var string
     */
    const login = "login";

    /**
     * @var string
     */
    const name = "name";

    /**
     * @var string
     */
    const password = "password";

    /**
     * @var string
     */
    const createdOn = "createdon";

    /**
     * @var string
     */
    const picURL = "picurl";

    /**
     * @var string
     */
    const isActive = "isactive";

    /**
     * @var string
     */
    const isAdmin = "isadmin";

    /**
     * @param User $user
     * @return bool
     */
    public function startSession(User $user)
    {
        $id = $user->getId();
        $login = $user->getLogin();
        $name = $user->getPassword();
        $password = $user->getPassword();
        $isActive = $user->getIsActive();
        $isAdmin = $user->getIsAdmin();
        $createdOn = $user->getCreatedOn();

        if (isset($user) && isset($id) && isset($login) && isset($password) &&
            isset($isActive) && isset($isAdmin) && isset($createdOn) &&
            isset($name))
        {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION[self::id] = $user->getId();
            $_SESSION[self::login] = $user->getLogin();
            $_SESSION[self::name] = $user->getName();
            $_SESSION[self::password] = $user->getPassword();
            $_SESSION[self::createdOn] = $user->getCreatedOn();
            $_SESSION[self::picURL] = $user->getPicURL();
            $_SESSION[self::isActive] = $user->getIsActive();
            $_SESSION[self::isAdmin] = $user->getIsAdmin();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isValid() {
        if (isset($_SESSION[self::login])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return null|string
     */
    public function getId() {
        if ($this->isValid()) {
            return $_SESSION[self::id];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getLogin() {
        if ($this->isValid()) {
            return $_SESSION[self::login];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getName() {
        if ($this->isValid()) {
            return $_SESSION[self::name];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getPassword() {
        if ($this->isValid()) {
            return $_SESSION[self::password];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getCreatedOn() {
        if ($this->isValid()) {
            return $_SESSION[self::createdOn];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getPicURL() {
        if ($this->isValid()) {
            return $_SESSION[self::picURL];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getisActive() {
        if ($this->isValid()) {
            return $_SESSION[self::isActive];
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getIsAdmin() {
        if ($this->isValid()) {
            return $_SESSION[self::isAdmin];
        } else {
            return null;
        }
    }

    /**
     * @return User|null
     */
    public function getUser() {
        if ($this->isValid()) {
            $user = new User();
            $user->setName($_SESSION[self::name]);
            $user->setLogin($_SESSION[self::login]);
            $user->setPassword($_SESSION[self::password]);
            $user->setCreatedOn($_SESSION[self::createdOn]);
            $user->setPicURL($_SESSION[self::picURL]);
            $user->setIsActive($_SESSION[self::isActive]);
            $user->setIsAdmin($_SESSION[self::isAdmin]);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * @param $user
     */
    public function setUset($user) {
        if ($this->isValid()) {
            $name = $user->getName();
            $password = $user->getPassword();
            $picURL = $user->getPicURL();
            if (isset($name)) {
                $_SESSION[self::name] = $user->getName();
            }
            if(isset($password)) {
                $_SESSION[self::password] = $user->getPassword();
            }
            if(isset($picURL)) {
                $_SESSION[self::picURL] = $user->getPicURL();
            }
        }
    }

    public function destroy() {
        session_destroy();
    }
}
