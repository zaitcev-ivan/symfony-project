<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\DeliveryMethod;
use PHPUnit\Framework\TestCase;

class DeliveryMethodTest extends TestCase
{
    /** @var DeliveryMethod */
    private $deliveryMethod;

    public function setUp()
    {
        $this->deliveryMethod = new DeliveryMethod();
    }

    public function testSettingName()
    {
        $this->deliveryMethod->setName($name = 'Name');
        $this->assertEquals($name, $this->deliveryMethod->getName());
    }

    public function testSettingCost()
    {
        $this->deliveryMethod->setCost($cost = 100);
        $this->assertEquals($cost, $this->deliveryMethod->getCost());
    }
}