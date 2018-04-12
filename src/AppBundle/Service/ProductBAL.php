<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;


class ProductBAL
{
    private $sessionManager;
    private $productDAL;
    private $validator;
    private $logger;
    private $rootDir;

    public function __construct(ProductDAL $productDAL, SessionManager $sessionManager, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->productDAL = $productDAL;
        $this->sessionManager = $sessionManager;
        $this->validator = $validator;
        $this->logger = $logger;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    public function validateProduct(Product $product)
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function addProduct(Product $product)
    {
        $product->setUpdatedOn(new \DateTime);
        $product->setUpdatedBy((int)$this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        $status = $this->validateProduct($product) && $this->productDAL->addProduct($product);
        $this->logger->info("Product:".$product->getName()." added by ".$this->sessionManager->getLogin()." at ".(string)$product->getUpdatedOn()->format("y:M:d:h:m:s"));
        return $status;
    }

    public function getProductById($id)
    {
        return $this->productDAL->getProductById($id);
    }

    public function getAllProducts()
    {
        return $this->productDAL->getAllProducts();
    }

    public function updateProduct(Product $product, $productId)
    {
        $product->setUpdatedOn(new \DateTime);
        $product->setUpdatedBy((int)$this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        $status = $this->validateProduct($product) && $this->productDAL->updateProduct($product, $productId);
        $this->logger->info("Product:".$product->getName()." modified by ".$this->sessionManager->getLogin()." at ".(string)$product->getUpdatedOn()->format("y:M:d:h:m:s"));
        return $status;
    }

    public function getProduct(Product $product)
    {
        $p = new Product();
        $p->setName($product->getName());
        $p->setDescription($product->getDescription());
        $p->setTypeId($product->getTypeId());
        $p->setPrice($product->getPrice());
        return $p;
    }

    public function deleteProduct($productId)
    {
        $this->logger->info("Product:".$productId." deleted by ".$this->sessionManager->getLogin()." at ".(string)(new \DateTime())->format("y:M:d:h:m:s"));
        return $this->productDAL->deleteProduct($productId);
    }

}
