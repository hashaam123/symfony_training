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


use AppBundle\Entity\User;

class UserController extends Controller
{

    /**
     * @route("/user/home")
     */
    public function home()
    {
        return $this->render("home.html.twig");
    }

    /**
     * @route("/user/signin")
     */
    public function signIn()
    {
        return $this->render("signin.html.twig");
    }

    /**
     * @route("/user/signup")
     */
    public function signUp(Request $request)
    {
        if(isset($request->get("form")["name"])) {
            $user = new User();
            $user->setName($request->get("form")["name"]);
            $user->setLogin($request->get("form")["login"]);
            $user->setPassword($request->get("form")["password"]);
            $user->setCreatedOn(new \DateTime());
            $user->setIsActive(true);

            return new Response($user->getName());
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

    /**
     * @route("/user/create")
     */
    public function createUser(Request $request)
    {
        $data = $request->get("form")["name"];

        return new Response($data);
    }


}

