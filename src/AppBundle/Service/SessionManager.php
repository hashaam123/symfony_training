<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class SessionManager
{
    private $id = "id";
    private $login = "login";
    private $name = "name";
    private $password = "password";
    private $createdOn = "createdon";
    private $picURL = "picurl";
    private $isActive = "isactive";
    private $isAdmin = "isadmin";

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
            $_SESSION[$this->id] = $user->getId();
            $_SESSION[$this->login] = $user->getLogin();
            $_SESSION[$this->name] = $user->getName();
            $_SESSION[$this->password] = $user->getPassword();
            $_SESSION[$this->createdOn] = $user->getCreatedOn();
            $_SESSION[$this->picURL] = $user->getPicURL();
            $_SESSION[$this->isActive] = $user->getIsActive();
            $_SESSION[$this->isAdmin] = $user->getIsAdmin();
            return true;
        } else {
            return false;
        }
    }

    public function isValid() {
        if (isset($_SESSION[$this->login])) {
            return true;
        } else {
            return false;
        }
    }

    public function getId() {
        if ($this->isValid()) {
            return $_SESSION[$this->id];
        } else {
            return null;
        }
    }

    public function getLogin() {
        if ($this->isValid()) {
            return $_SESSION[$this->login];
        } else {
            return null;
        }
    }

    public function getName() {
        if ($this->isValid()) {
            return $_SESSION[$this->name];
        } else {
            return null;
        }
    }

    public function getPassword() {
        if ($this->isValid()) {
            return $_SESSION[$this->password];
        } else {
            return null;
        }
    }

    public function getCreatedOn() {
        if ($this->isValid()) {
            return $_SESSION[$this->createdOn];
        } else {
            return null;
        }
    }

    public function getPicURL() {
        if ($this->isValid()) {
            return $_SESSION[$this->picURL];
        } else {
            return null;
        }
    }

    public function getisActive() {
        if ($this->isValid()) {
            return $_SESSION[$this->isActive];
        } else {
            return null;
        }
    }

    public function getIsAdmin() {
        if ($this->isValid()) {
            return $_SESSION[$this->isAdmin];
        } else {
            return null;
        }
    }

    public function getUser() {
        if ($this->isValid()) {
            $user = new User();
            $user->setName($_SESSION[$this->name]);
            $user->setLogin($_SESSION[$this->login]);
            $user->setPassword($_SESSION[$this->password]);
            $user->setCreatedOn($_SESSION[$this->createdOn]);
            $user->setPicURL($_SESSION[$this->picURL]);
            $user->setIsActive($_SESSION[$this->isActive]);
            $user->setIsAdmin($_SESSION[$this->isAdmin]);
            return $user;
        } else {
            return null;
        }
    }

    public function setUset($user) {
        if ($this->isValid()) {
            $name = $user->getName();
            $password = $user->getPassword();
            $picURL = $user->getPicURL();
            if (isset($name)) {
                $_SESSION[$this->name] = $user->getName();
            }
            if(isset($password)) {
                $_SESSION[$this->password] = $user->getPassword();
            }
            if(isset($picURL)) {
                $_SESSION[$this->picURL] = $user->getPicURL();
            }
        }
    }

    public function destroy() {
        session_destroy();
    }
}
