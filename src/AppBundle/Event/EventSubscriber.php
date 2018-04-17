<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use AppBundle\Entity\Product;
use AppBundle\Entity\Service;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;

class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_delete' => array('setOrderCost'),
        );
    }

    public function setOrderCost()
    {
    }
}
