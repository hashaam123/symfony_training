<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AppBundle\Entity\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseAdminController
{
    protected function prePersistEntity($entity)
    {
        if ($entity instanceof Orders) {
            $services = $entity->getServices();
            $products = $entity->getProducts();
            $cost = 0;
            foreach ($services as $service) {
                $cost += $service->getPrice();
            }
            foreach ($products as $product) {
                $cost += $product->getCost();
            }
            $entity->setCost($cost);
            $entity->setUserId($entity->getUser()->getId());
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
            $entity->setUserId($entity->getUser()->getId());
            if($entity->getIsAccepted() == null) {
                $entity->setIsAccepted(false);
            }
        }
    }

    public function invoiceAction()
    {
        $orderId = $this->request->get("id");
        $order = $this->em->getRepository(Orders::class)->findOneById($orderId);
        $services = $order->getServices();
        return $this->render("invoice.html.twig", [ "order" => $order ] );
    }
}
