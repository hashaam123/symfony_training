<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use AppBundle\Entity\Product;
use AppBundle\Entity\Service;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_list' => array('setBlogPostSlug'),
        );
    }

    public function setBlogPostSlug(GenericEvent $event)
    {
    }
}
