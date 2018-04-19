<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AppBundle\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseAdminController
{
    protected function preListEntity($entity)
    {
        file_put_contents("/home/coeus/Desktop/file.txt", "sadf");
    }

    protected function prePersistEntity($entity)
    {
        $entity->setDateTime(new \DateTime());
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
        return $this->render("invoice.html.twig", [ "order" => $order ] );
    }

    public function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        if ($entityClass == "AppBundle\Entity\Orders") {
            $query = $this->em->createQuery(
                'SELECT year(p.dateTime), sum(p.cost)
                FROM AppBundle:Orders p
                WHERE p.isAccepted = true
                group by year(p.dateTime)');
            return $query;

        } else {
            return parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
        }
    }
}
