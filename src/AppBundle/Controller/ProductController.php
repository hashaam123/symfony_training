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
use AppBundle\Service\SessionManager;
use AppBundle\Service\ProductBAL;
use AppBundle\Form\ProductType;


class ProductController extends Controller
{
    private $sessionManager;
    private $productBAL;

    public function __construct(SessionManager $sessionManager, ProductBAL $productBAL)
    {
        $this->sessionManager = $sessionManager;
        $this->productBAL = $productBAL;
    }

    public function addProductAction(Request $request)
    {
        if ($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {
            $form = $this->createForm(ProductType::class, new Product());
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $product = $form->getData();
                $product->setPicURL($form['picurl']->getData());
                $this->productBAL->addProduct($product);
                return $this->redirectToRoute("home_user");
            } else {
                return $this->render('addproduct.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function getProductAction($id)
    {
        if ($this->sessionManager->isValid()) {
            if (isset($id)) {
                $product = $this->productBAL->getProductById($id);
                if (empty($product)) {
                    throw $this->createNotFoundException("product not found");
                } else {
                    $product = array($product);
                    return $this->render("products.html.twig", array('products' => $product));
                }
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function getProductsAction()
    {
        if ($this->sessionManager->isValid()) {
            $products = $this->productBAL->getAllProducts();

            if (empty($products)) {
                throw $this->createNotFoundException(
                    'No product found for id '
                );
            } else {
                return $this->render("products.html.twig", array('products' => $products));
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }

    }

    public function updateProductAction(Request $request)
    {
        if($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {

            $productId = $request->get("id");
            $prod = $this->productBAL->getProduct($this->productBAL->getProductById($productId));
            $form = $this->createForm(ProductType::class, $prod);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $productId = $_SESSION["id"];
                $product = $form->getData();
                $product->setPicURL($form['picurl']->getData());
                $this->productBAL->updateProduct($product, $productId);
                return $this->redirectToRoute("home_user");

            } else {

                $_SESSION["id"] = $productId;
                return $this->render('edit_product.html.twig', array(
                    'form' => $form->createView(),
                ));

            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    public function deleteProductAction(Request $request)
    {
        if ($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {
            $productId = $request->get("id");
            if (!empty($productId)) {
                if ($this->productBAL->deleteProduct($productId)) {
                    return new Response(json_encode("true"));
                }
            }
            return new Response(json_encode("false"));
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    /**
     * @route("/test")
     */
    public function test()
    {
        $str = '%kernel.root_dir%/../web/';
        return new Response($str);
    }
}

