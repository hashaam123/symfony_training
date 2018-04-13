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
use AppBundle\Service\ProductService;
use AppBundle\Form\ProductType;

/**
 * Class ProductController
 * @package AppBundle\Controller
 * @route("/product")
 */
class ProductController extends Controller
{
    /**
     * @var string
     */
    const productError = "Product error occured";

    /**
     * @var SessionManager
     * @package AppBundle\Service
     */
    private $sessionManager;

    /**
     * @var ProductService
     * @package AppBundle\Service
     */
    private $productService;

    /**
     * ProductController constructor.
     * @param SessionManager $sessionManager
     * @param ProductService $productService
     */
    public function __construct(SessionManager $sessionManager, ProductService $productService)
    {
        $this->sessionManager = $sessionManager;
        $this->productService = $productService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/add", name="add_product")
     */
    public function addProductAction(Request $request)
    {
        if ($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {
            $form = $this->createForm(ProductType::class, new Product());
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $product = $form->getData();
                $product->setPicURL($form['picurl']->getData());
                $status = $this->productService->addProduct($product);
                if ($status === true) {
                    return $this->redirectToRoute("home_user");
                } else {
                    throw $this->createNotFoundException($this->get('translator')->trans(self::productError));
                }
            } else {
                return $this->render('addproduct.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/getproduct/{id}", name="get_product")
     */
    public function getProductAction($id)
    {
        if ($this->sessionManager->isValid()) {
            if (isset($id)) {
                $product = $this->productService->getProductById($id);
                if (empty($product)) {
                    throw $this->createNotFoundException($this->get('translator')->trans(self::productError));
                } else {
                    $product = [$product];
                    return $this->render("products.html.twig", ['products' => $product]);
                }
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }


    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/getproducts", name="get_products")
     */
    public function getProductsAction()
    {
        if ($this->sessionManager->isValid()) {
            $products = $this->productService->getAllProducts();

            if (empty($products)) {
                throw $this->createNotFoundException(
                    $this->get('translator')->trans(self::productError)
                );
            } else {
                return $this->render("products.html.twig", ['products' => $products]);
            }
        } else {
            return $this->redirectToRoute("signin_user");
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/update", name="update_product")
     */
    public function updateProductAction(Request $request)
    {
        if($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {

            $productId = $request->get("id");
            $prod = $this->productService->getProduct($this->productService->getProductById($productId));
            $form = $this->createForm(ProductType::class, $prod);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $productId = $_SESSION["id"];
                $product = $form->getData();
                $product->setPicURL($form['picurl']->getData());
                $status = $this->productService->updateProduct($product, $productId);
                return $this->redirectToRoute("home_user");
            } else {

                $_SESSION["id"] = $productId;
                return $this->render('edit_product.html.twig', [
                    'form' => $form->createView(),
                ]);

            }
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @route("/delete", name="delete_product")
     */
    public function deleteProductAction(Request $request)
    {
        if ($this->sessionManager->isValid() && $this->sessionManager->getIsAdmin()) {
            $productId = $request->get("id");
            if (!empty($productId)) {
                if ($this->productService->deleteProduct($productId)) {
                    return new Response("true");
                }
            }
            return new Response("false");
        } else {
            return $this->redirectToRoute("signin_user");
        }
    }
}
