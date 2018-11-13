<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\DeliveryMethod;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /** @var Order */
    private $order;

    public function setUp()
    {
        $this->order = new Order();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->order->getStatus());
        $this->assertInstanceOf(ArrayCollection::class, $this->order->getItems());
    }

    public function testSettingNewStatus()
    {
        $order = $this->order->addCurrentStatus(Order::NEW);
        $this->assertTrue($this->order->isNew());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingPaidStatus()
    {
        $order = $this->order->paid();
        $this->assertTrue($this->order->isPaid());
        $this->assertTrue($this->order->isCanSent());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingSentStatus()
    {
        $order = $this->order->sent();
        $this->assertTrue($this->order->isSent());
        $this->assertTrue($this->order->isCanComplete());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingCompleteStatus()
    {
        $order = $this->order->completed();
        $this->assertEquals(Order::COMPLETED, $this->order->getCurrentStatus());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingRejectStatus()
    {
        $order = $this->order->reject();
        $this->assertTrue($this->order->isReject());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingRejectByCustomerStatus()
    {
        $order = $this->order->rejectByCustomer($reason = 'Reason');
        $this->assertEquals(Order::CANCELLED_BY_CUSTOMER, $this->order->getCurrentStatus());
        $this->assertEquals($reason, $this->order->getCancelReason());
        $this->assertInstanceOf(OrderStatus::class, $this->order->getStatus()[0]);
        $this->assertEquals($order, $this->order->getStatus()[0]);
    }

    public function testSettingDeliveryMethod()
    {
        $deliveryMethod = new DeliveryMethod();
        $deliveryMethod->setName($deliveryMethodName = 'Delivery method');
        $deliveryMethod->setCost($deliveryMethodCost = 100);

        $this->order->addDeliveryMethod($deliveryMethod);
        $this->assertInstanceOf(DeliveryMethod::class, $this->order->getDeliveryMethod());
        $this->assertEquals($deliveryMethodName, $this->order->getDeliveryMethodName());
        $this->assertEquals($deliveryMethodCost, $this->order->getDeliveryMethodCost());
    }

    public function testSettingCost()
    {
        $this->order->setCost($cost = 1000);
        $this->assertEquals($cost, $this->order->getCost());
    }

    public function testSettingNote()
    {
        $this->order->setNote($note = 'note');
        $this->assertEquals($note, $this->order->getNote());
    }

    public function testSettingUserData()
    {
        $this->order->setUserName($userName = 'User name');
        $this->order->setUserPhone($userPhone = '+79111111111');
        $this->assertEquals($userName, $this->order->getUserName());
        $this->assertEquals($userPhone, $this->order->getUserPhone());
    }

    public function testAddItems()
    {
        $this->assertEquals(0, \count($this->order->getItems()));
        $this->order->addItem(new OrderItem());
        $this->assertEquals(1, \count($this->order->getItems()));
    }
}