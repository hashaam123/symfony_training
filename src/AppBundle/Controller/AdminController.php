<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use AppBundle\Entity\Sales;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AppBundle\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends BaseAdminController
{
    /**
     * override parent::prePersistEntity
     *
     * @param object $entity
     */
    protected function prePersistEntity($entity)
    {
        $entity->setDateTime(new \DateTime());
        if ($entity instanceof Orders) {
            /*
             * calculating total cost of user order which may
             * inlude some services and products
             */
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

    /**
     * override parent::preUpdateEntity
     *
     * @param object $entity
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
            /**
             * if order has been delivered then it record
             * should put inside sale
             */
            if ($entity->getIsAccepted() == true) {
                $sales = new Sales();
                $sales->setOrderId($entity->getId());
                $sales->setCost($entity->getCost());
                $sales->setDateTime($entity->getDateTime());
                $this->em->persist($sales);
                $this->em->flush();
            }
        }
    }

    /**
     * custom action function for generating invoice/bill
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function invoiceAction()
    {
        $orderId = $this->request->get("id");
        $order = $this->em->getRepository(Orders::class)->findOneById($orderId);
        return $this->render("invoice.html.twig", [ "order" => $order ] );
    }

    /**
     * override parent::listAction
     *
     * @return Response
     */
    protected function listAction()
    {
        if ($this->entity['class'] == "AppBundle\Entity\Sales") {
            $sales = $this->em->getRepository(Sales::class)->findAll();
            $profits =[];
            /**
             * calculating sales year wise
             */
            foreach ($sales as $sale) {
                $sale->setDateTime($sale->getDateTime()->format("y"));
                if (isset($profits[$sale->getDateTime()])) {
                    $profits[$sale->getDateTime()][1] += $sale->getCost();
                } else {
                    $profits[$sale->getDateTime()] = [$sale->getDateTime(), $sale->getCost()];
                }
            }
            /**
             * only columns to be displayed
             */
            $fields = ["Year", "Sale"];
            return $this->render("sales.html.twig", array("sales" => $profits, "fields" => $fields));
        } else {
            /**
             * if enity is other than sales then default
             * function should be called
             */
            return parent::listAction();
        }
    }
}
