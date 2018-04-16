<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class ProductService
 * @package AppBundle\Service
 */
class ProductService
{
    /**
     * @var EntityManager
     * @package Doctrine\ORM;
     */
    private $entityManager;

    /**
     * @var SessionManager
     * @package AppBundle\Service
     */
    private $sessionManager;

    /**
     * @var ValidatorInterface
     * @package Symfony\Component\Validator\Validator
     */
    private $validator;

    /**
     * @var LoggerInterface
     * @package Psr\Log
     */
    private $logger;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * ProductService constructor.
     * @param Doctrine\ORM\EntityManager $entityManager
     * @param SessionManager $sessionManager
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(EntityManager $entityManager, SessionManager $sessionManager, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
        $this->validator = $validator;
        $this->logger = $logger;
        $this->rootDir = "/home/coeus/Desktop/Untitled Folder/symfony_training";
    }

    /**
     * @param AppBundle\Entity\Product $product
     * @return bool|string
     */
    public function validateProduct(Product $product)
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return (string)$errors;
        } else {
            return true;
        }
    }

    /**
     * @param AppBundle\Entity\Product $product
     * @return Product
     */
    public function getProduct(Product $product)
    {
        $newProduct = new Product();
        $newProduct->setName($product->getName());
        $newProduct->setDescription($product->getDescription());
        $newProduct->setTypeId($product->getTypeId());
        $newProduct->setPrice($product->getPrice());
        return $newProduct;
    }

    /**
     * @param AppBundle\Entity\Product $product
     * @return bool|string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addProduct(Product $product)
    {
        $product->setUpdatedOn(new \DateTime);
        $product->setUpdatedBy((int)$this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        $status = $this->validateProduct($product);
        if ($status === true) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->logger->info("Product add", [$product->getName(), $this->sessionManager->getLogin(), (string)$product->getUpdatedOn()->format("y:M:d:h:i:s")]);
        }
        return $status;
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getProductById($productId)
    {
        return $this->entityManager->getRepository(Product::class)->findOneById($productId);
    }

    /**
     * @return array
     */
    public function getAllProducts()
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    /**
     * @param AppBundle\Entity\Product $product
     * @param $productId
     * @return bool|string
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateProduct(Product $product, $productId)
    {
        $product->setUpdatedOn(new \DateTime);
        $product->setUpdatedBy((int)$this->sessionManager->getId());
        $product->setIsActive(true);
        $file = $product->getPicURL();
        $fileName = md5(uniqid()) . "." . $file->guessExtension();
        $file->move($this->rootDir . '/web/uploads/', $fileName);
        $product->setPicURL($fileName);
        $status = $this->validateProduct($product);
        if ($status === true) {
            $storedProduct = $this->entityManager->getRepository(Product::class)->find($productId);
            $storedProduct->setName($product->getName());
            $storedProduct->setDescription($product->getDescription());
            $storedProduct->setPrice($product->getPrice());
            $storedProduct->setPicURL($product->getPicURL());
            $storedProduct->setTypeId($product->getTypeId());
            $storedProduct->setUpdatedBy($product->getUpdatedBy());
            $storedProduct->setUpdatedOn($product->getUpdatedOn());
            $this->entityManager->flush();
            $this->logger->info("Product modified", [$product->getName(), $this->sessionManager->getLogin(), (string)$storedProduct->getUpdatedOn()->format("y:M:d:h:i:s")]);
        }
        return $status;
    }

    /**
     * @param string $productName
     * @param int $productId
     * @return bool
     */
    public function updateProductById(int $productId, string $productName)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!empty($product)) {
            $product->setName($productName);
            $product->setUpdatedOn(new \DateTime());
            $product->setUpdatedBy(1);
            $this->entityManager->flush();
            $this->logger->info("Product modified", [$product->getName(), $this->sessionManager->getLogin(), (string)$product->getUpdatedOn()->format("y:M:d:h:i:s")]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $productId
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteProduct($productId)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        $this->logger->info("Product deleted", [$product->getName(), $this->sessionManager->getLogin(), (string)$product->getUpdatedOn()->format("y:M:d:h:i:s")]);
        return true;
    }
}
