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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use AppBundle\Entity\Product;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;



class ProductController extends Controller
{
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
            $file->move($this->get('kernel')->getRootDir() . '/../web/uploads', $fileName);

            $product->setPicURL($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute("productadd");
        } else {



            $product = new Product();
            $form = $this->createFormBuilder($product)
                ->add('name', TextType::class)
                ->add('price', NumberType::class)
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

    public function getProductAction($id)
    {
        if(isset($id)) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            if(isset($product)) {
                $product = array($product);
                return $this->render("products.html.twig", array('products' => $product));
            }
        }
        throw $this->createNotFoundException("product not found");
    }

    public function getProductsAction()
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)->findAll();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        } else {
            return $this->render("products.html.twig", array('products' => $product));
        }

    }

    public function updateProductAction(Request $request)
    {
        if($request->get("form")["id"]) {
            $productId = $request->get("form")["id"];
            $product = new Product();
            $product->setName($request->get("form")["name"]);
            $product->setDescription($request->get("form")["description"]);
            $product->setPrice($request->get("form")["price"]);
            $product->setTypeId($request->get("form")["typeid"]);
            $product->setUpdatedOn(new \DateTime);
            $product->setUpdatedBy(11);
            $file = $request->files->get("form")["picurl"];
            if(isset($file)) {
                $fileName = md5(uniqid()) . "." . $file->guessExtension();
                $fileSystem = new FileSystem();
                $file->move($this->get('kernel')->getRootDir() . '/../web/uploads', $fileName);
                $product->setPicURL($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $prod = $entityManager->getRepository(Product::class)->find($productId);
            if(!$prod) {
                throw $this->createNotFoundException("product not found");
            } else {
                if($product->getName()) {
                    $prod->setName($product->getName());
                }
                if($product->getPicURL()) {
                    $prod->setPicURL($product->getPicURL());
                }
                if($product->getDescription()) {
                    $prod->setDescription($product->getDescription());
                }
                if($product->getPrice()) {
                    $prod->setPrice($product->getPrice());
                }
                if($product->getTypeId()) {
                    $prod->setTypeId($product->getTypeId());
                }
                if($product->getUpdatedBy()) {
                    $prod->setUpdatedBy($product->getUpdatedBy());
                }
                $prod->setUpdatedOn($product->getUpdatedOn());
                $entityManager->flush();
                return $this->redirectToRoute("getproducts");
            }
        } else {
            throw $this->createNotFoundException("product not found");
        }
    }

    public function editProductAction(Request $request)
    {

        $productId = $request->get("id");
        if(isset($productId)) {
            $prod = $this->getDoctrine()->getRepository(Product::class)->find($productId);
            if (isset($prod)) {
                $product = new Product();
                $form = $this->createFormBuilder($product)
                    ->add('id', TextType::class, array('attr' => array('class' => 'id', 'value' => $productId)))
                    ->add('name', TextType::class, array('attr' => array('class' => 'name', 'value' => $prod->getName())))
                    ->add('price', NumberType::class, array('attr' => array('class' => 'price', 'value' => $prod->getPrice())))
                    ->add("typeid", IntegerType::class, array('attr' => array('class' => 'typeid', 'value' => $prod->getTypeId())))
                    ->add("description", TextType::class, array('attr' => array('class' => 'description', 'value' => $prod->getDescription())))
                    ->add("picurl", FileType::class, array('attr' => array('class' => 'picURL')))
                    ->add('Submit', SubmitType::class, array('label' => 'Edit Product'))
                    ->setMethod('post')
                    ->setAction('/product/update')
                    ->getForm();

                return $this->render('edit_product.html.twig', array('form' => $form->createView()));
            }
        }
        throw $this->createNotFoundException("product not found");

    }

    public function deleteProductAction(Request $request)
    {
        $productId = $request->get("id");
        if(isset($productId)) {
            $entityManager = $this->getDoctrine()->getManager();
            $product = $entityManager->getRepository(Product::class)->find($productId);
            $fileSystem = new FileSystem();
            $fileSystem->remove($this->get('kernel')->getRootDir() . '/../web/uploads' . '/' . $product->getPicURL());
            $entityManager->remove($product);
            $entityManager->flush();
            return new Response(json_encode("true"));
        } else {
            return new Response(json_encode("false"));
        }
    }
}

