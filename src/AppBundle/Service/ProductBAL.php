<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;

class ProductBAL
{
    private $sessionManager;
    private $productDAL;
    private $rootDir;

    public function __construct(ProductDAL $productDAL, SessionManager $sessionManager)
    {
        $this->productDAL = $productDAL;
        $this->sessionManager = $sessionManager;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    public function addProduct(Product $product)
    {
        $product->setUpdatedOn(new \DateTime);
        $product->setUpdatedBy($this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        if($this->productDAL->addProduct($product)) {
            return true;
        } else {
            return false;
        }
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
        $product->setUpdatedBy($this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        if ($this->productDAL->updateProduct($product, $productId)) {
            return true;
        } else {
            return false;
        }
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











