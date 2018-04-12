<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductBAL
{
    private $sessionManager;
    private $productDAL;
    private $validator;
    private $rootDir;

    public function __construct(ProductDAL $productDAL, SessionManager $sessionManager, ValidatorInterface $validator)
    {
        $this->productDAL = $productDAL;
        $this->sessionManager = $sessionManager;
        $this->validator = $validator;
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
        return $this->productDAL->deleteProduct($productId);
    }

}
