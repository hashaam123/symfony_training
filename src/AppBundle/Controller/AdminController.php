<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AppBundle\Entity\Service;

class AdminController extends BaseAdminController
{
    protected function prePersistEntity($entity)
    {
        if ($entity instanceof Orders) {
            $services = $entity->getServices();
            $cost = 0;
            foreach ($services as $service) {
                $cost += $service->getPrice();
            }
            $products = $entity->getProducts();
            foreach ($products as $product) {
                $cost += $product->getCost();
            }
            $entity->setCost($cost);
            $entity->setUserId($entity->getUserIds()->getId());
            if($entity->getIsAccepted() == null) {
                $entity->setIsAccepted(false);
            }
        }
    }

    protected function preUpdateEntity($entity)
    {
        if ($entity instanceof Orders) {
            $services = $entity->getServices();
            $cost = 0;
            foreach ($services as $service) {
                $cost += $service->getPrice();
            }
            $products = $entity->getProducts();
            foreach ($products as $product) {
                $cost += $product->getCost();
            }
            $entity->setCost($cost);
            $entity->setUserId($entity->getUserIds()->getId());
            if($entity->getIsAccepted() == null) {
                $entity->setIsAccepted(false);
            }
        }
    }
}
