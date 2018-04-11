<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class ProductDAL
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addProduct(Product $product)
    {
        try {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return true;
        } catch(Throwable $throwable) {
            return false;
        }
    }

    public function getProductById($productId) {
        try {
            return $this->entityManager->getRepository(Product::class)->findOneById($productId);
        } catch (Throwable $throwable) {
            return null;
        }
    }

    public function getAllProducts() {
        try{
            return $this->entityManager->getRepository(Product::class)->findAll();
        } catch (Throwable $throwable) {
            return null;
        }
    }

    public function updateProduct(Product $product, $productId)
    {
        try {
            $prod = $this->entityManager->getRepository(Product::class)->find($productId);
            $prod->setName($product->getName());
            $prod->setDescription($product->getDescription());
            $prod->setPrice($product->getPrice());
            $prod->setPicURL($product->getPicURL());
            $prod->setTypeId($product->getTypeId());
            $prod->setUpdatedBy($product->getUpdatedBy());
            $prod->setUpdatedOn($product->getUpdatedOn());
            $this->entityManager->flush();
        } catch (Throwable $throwable) {
            return false;
        }
    }

    public function deleteProduct($productId)
    {
        try {
            $prod = $this->entityManager->getRepository(Product::class)->find($productId);
            $this->entityManager->remove($prod);
            $this->entityManager->flush();
            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }
}
