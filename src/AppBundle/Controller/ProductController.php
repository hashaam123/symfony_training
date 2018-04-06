<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Date;

class ProductController extends Controller
{
    /**
     * @route("/add", name="productadd")
     */
    public function addProductAction(Request $request)
    {
        if($request->get("form")["name"]) {

            $product = new Product();
            $product->setName($request->get("form")["name"]);
            $product->setDescription($request->get("form")["description"]);
            $product->setPrice($request->get("form")["price"]);
            $product->setTypeId($request->get("form")["typeid"]);
            $product->setUpdatedOn(new \DateTime);
            $product->setUpdatedBy(11);
            $product->setIsActive(true);

            $file = $request->files->get("form")["picurl"];
            $fileName = md5(uniqid()) . "." . $file->guessExtension();
            $file->move($this->getParameter('uploads_directory'), $fileName);

            $product->setPicURL($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute("productadd");
        } else {



            $product = new Product();
            $form = $this->createFormBuilder($product)
                ->add('name', TextType::class)
                ->add('price', NumberType::class, array('attr' => array('id' => 'someid')))
                ->add("typeid", IntegerType::class)
                ->add("description", TextType::class)
                ->add("picurl", FileType::class)
                ->add('Submit', SubmitType::class, array('label' => 'Add Product'))
                ->setMethod('post')
                ->getForm();

            return $this->render('addproduct.html.twig', array(
                'form' => $form->createView(),
            ));


        }
    }

}

