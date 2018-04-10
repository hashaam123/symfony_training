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


use AppBundle\Entity\User;
use AppBundle\Entity\Product;


class UserController extends Controller
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function homeAction(Request $request)
    {
        if($this->sessionManager->isValid()) {
            $login = $request->get("login");
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            $user = $this->sessionManager->getUser();
            return $this->render("home.html.twig", array("user" => $user, "products" => $products));
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function signInAction(Request $request)
    {
        if(isset($request->get("form")["login"])) {
            $login = $request->get("form")["login"];
            $password = $request->get("form")["password"];
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByLogin($login);
            if(isset($user) && $user->getPassword() == $password) {
                $this->sessionManager->startSession($user);
                return $this->redirectToRoute("home_user", array("login" => $login));
            }
            throw $this->createNotFoundException("user does not exist");
        } else {

            $user = new User();
            $form = $this->createFormBuilder($user)
                ->add('login', TextType::class)
                ->add("password", PasswordType::class)
                ->setMethod('post')
                ->add('Submit', SubmitType::class, array('label' => 'Sign In'))
                ->getForm();

            return $this->render('signin.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function signUpAction(Request $request)
    {
        if(isset($request->get("form")["name"])) {
            $user = new User();
            $user->setName($request->get("form")["name"]);
            $user->setLogin($request->get("form")["login"]);
            $user->setPassword($request->get("form")["password"]);
            $user->setCreatedOn(new \DateTime());
            $user->setIsActive(true);
            $user->setIsAdmin(false);

            $file = $request->files->get("form")["picurl"];
            $fileName = md5(uniqid()) . "." . $file->guessExtension();
            $user->setPicURL($fileName);
            try {
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();
                $file->move($this->get('kernel')->getRootDir() . '/../web/uploads', $fileName);
            } catch (Throwable $t) {
                throw $this->createNotFoundException("record could not be inserted");
            }
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByLogin($login);
            $this->sessionManager->startSession($user);
            return $this->redirectToRoute("home_user");
        } else {

            $user = new User();
            $form = $this->createFormBuilder($user)
                ->add('login', TextType::class)
                ->add('name', TextType::class, array('attr' => array('id' => 'someid')))
                ->add("password", PasswordType::class)
                ->add("picurl", FileType::class)
                ->add('Submit', SubmitType::class, array('label' => 'Sign Up'))
                ->setMethod('post')
                ->getForm();

            return $this->render('signup.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function updateProfileAction(Request $request)
    {
        if ($this->sessionManager->isValid()) {
            $user = $this->sessionManager->getUser();
            $form = $this->createFormBuilder(new User())
                ->add('login', TextType::class, array('attr' => array('value' => $user->getLogin())))
                ->add('name', TextType::class, array('attr' => array('value' => $user->getName())))
                ->add("password", PasswordType::class, array('attr' => array('value' => $user->getPassword())))
                ->add("picurl", FileType::class)
                ->add('Submit', SubmitType::class, array('label' => 'Sign Up'))
                ->setMethod('post')
                ->getForm();
            if ($request->get("form")["login"]) {
                $entityManager = $this->getDoctrine()->getManager();
                $user = new User();
                $user->setLogin($request->get("form")["login"]);
                $user->setName($request->get("form")["name"]);
                $user->setPassword($request->get("form")["password"]);

                $file = $request->files->get("form")["picurl"];
                if (isset($file)) {
                    $fileName = md5(uniqid()) . "." . $file->guessExtension();
                    $user->setPicURL($fileName);
                    $user->setPicURL($fileName);
                    $previousFile = $entityManager->getRepository(User::class)->findOneByLogin($this->sessionManager->getLogin())->getPicURL();
                    $u = $entityManager->getRepository(User::class)->findOneByLogin($user->getLogin());
                    $u->setName($user->getName());
                    $u->setPassword($user->getPassword());
                    $u->setPicURL($user->getPicURL());
                    $entityManager->flush();
                    $this->sessionManager->setUset($u);
                    $fileSystem = new FileSystem();
                    $file->move($this->get('kernel')->getRootDir() . '/../web/uploads', $fileName);
                    $fileSystem->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $previousFile);
                    return $this->redirectToRoute("home_user");
                }

            }
            return $this->render('update_profile.html.twig', array(
                'form' => $form->createView(),
            ));

        }
        return $this->redirectToRoute("signin_user");
    }

    public function logoutAction()
    {
        $this->sessionManager->destroy();
        return $this->redirectToRoute("signin_user");
    }
}

