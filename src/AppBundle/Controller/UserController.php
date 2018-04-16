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
use AppBundle\Service\UserService;
use AppBundle\Service\ProductService;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Entity\Product;


/**
 * Class UserController
 * @package AppBundle\Controller
 * @route("/user")
 */
class UserController extends Controller
{
    /**
     * @var string
     */
    const signIn = "Sign In";

    /**
     * @var string
     */
    const userError = "User error occured";

    /**
     * @var SessionManager
     * @package AppBundle\Service
     */
    private $sessionManager;

    /**
     * @var UserService
     * @package AppBundle\Service
     */
    private $userService;

    /**
     * @var ProductService
     * @package AppBundle\Service
     */
    private $productService;

    /**
     * UserController constructor.
     * @param SessionManager $sessionManager
     * @param UserService $userService
     * @param ProductService $productService
     */
    public function __construct(SessionManager $sessionManager, UserService $userService, ProductService $productService)
    {
        $this->sessionManager = $sessionManager;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/home", name="home_user")
     */
    public function homeAction(Request $request)
    {
        if($this->sessionManager->isValid()) {
            $products = $this->productService->getAllProducts();
            $user = $this->sessionManager->getUser();
            return $this->render("home.html.twig", ["user" => $user, "products" => $products]);
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/signin", name="signin_user")
     */
    public function signInAction(Request $request)
    {
        $form = $this->createForm(UserType::class, new User(), ['type' => self::signIn]);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $u = $form->getData();
            $user = $this->userService->getUserByLogin($u->getLogin());
            if(!empty($user) && $user->getPassword() == $u->getPassword()) {
                $this->sessionManager->startSession($user);
                return $this->redirectToRoute("home_user", ["login" => $u->getName()]);
            }
            throw $this->createNotFoundException($this->get('translator')->trans(self::userError));
        } else {
            return $this->render('signin.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/signup", name="signup_user")
     */
    public function signUpAction(Request $request)
    {
        $form = $this->createForm(UserType::class, null);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $user = $form->getData();
            $user->setPicURL($form['picurl']->getData());
            if($this->userService->addUser($user)) {
                $user = $this->userService->getUserByLogin($user->getLogin());
                $this->sessionManager->startSession($user);
                return $this->redirectToRoute("home_user");
            } else {
                throw $this->createNotFoundException($this->get('translator')->trans(self::userError));
            }
        } else {
            return $this->render('signup.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/update", name="update_user")
     */
    public function updateProfileAction(Request $request)
    {
        if ($this->sessionManager->isValid()) {
            $user = $this->userService->getUser($this->sessionManager->getUser());
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $updatedUser = $form->getData();
                $updatedUser->setPicURL($form['picurl']->getData());
                $this->userService->updateUser($updatedUser);
                return $this->redirectToRoute("home_user");
            } else {
                return $this->render('update_profile.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @route("/logout", name="logout_user")
     */
    public function logoutAction()
    {
        $this->sessionManager->destroy();
        return $this->redirectToRoute("signin_user");
    }
}
