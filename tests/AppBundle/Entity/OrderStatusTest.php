<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    public function testCreate()
    {
        $orderStatus = new OrderStatus($status = Order::NEW, $createdAt = new \DateTime());
        $this->assertEquals($status, $orderStatus->getValue());
        $this->assertEquals($createdAt, $orderStatus->getCreatedAt());
    }
}