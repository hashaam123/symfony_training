<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Service\SessionManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use AppBundle\Service\UserBAL;
use AppBundle\Service\ProductBAL;
use AppBundle\Form\UserType;

use AppBundle\Entity\User;
use AppBundle\Entity\Product;


class UserController extends Controller
{
    private $sessionManager;
    private $userBAL;
    private $productBAL;

    public function __construct(SessionManager $sessionManager, UserBAL $userBAL, ProductBAL $productBAL)
    {
        $this->sessionManager = $sessionManager;
        $this->userBAL = $userBAL;
        $this->productBAL = $productBAL;
    }

    public function homeAction(Request $request)
    {
        if($this->sessionManager->isValid()) {
            $products = $this->productBAL->getAllProducts();
            $user = $this->sessionManager->getUser();
            return $this->render("home.html.twig", array("user" => $user, "products" => $products));
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function signInAction(Request $request)
    {
        $form = $this->createForm(UserType::class, new User(), array('type' => 'signin'));
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $u = $form->getData();
            $user = $this->userBAL->getUserByLogin($u->getLogin());
            if(!empty($user) && $user->getPassword() == $u->getPassword()) {
                $this->sessionManager->startSession($user);
                return $this->redirectToRoute("home_user", array("login" => $u->getName()));
            }
            throw $this->createNotFoundException("user does not exist");
        } else {
            return $this->render('signin.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function signUpAction(Request $request)
    {
        $form = $this->createForm(UserType::class, null);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $user = $form->getData();
            $user->setPicURL($form['picurl']->getData());
            if($this->userBAL->addUser($user)) {
                $user = $this->userBAL->getUserByLogin($user->getLogin());
                $this->sessionManager->startSession($user);
                return $this->redirectToRoute("home_user");
            } else {
                throw $this->createNotFoundException("record could not be inserted");
            }
        } else {
            return $this->render('signup.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function updateProfileAction(Request $request)
    {
        if ($this->sessionManager->isValid()) {
            $user = $this->userBAL->getUser($this->sessionManager->getUser());
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $updatedUser = $form->getData();
                $updatedUser->setPicURL($form['picurl']->getData());
                $this->userBAL->updateUser($updatedUser);
                return $this->redirectToRoute("home_user");
            } else {
                return $this->render('update_profile.html.twig', array(
                    'form' => $form->createView(),
                ));
            }

        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function logoutAction()
    {
        $this->sessionManager->destroy();
        return $this->redirectToRoute("signin_user");
    }
}

