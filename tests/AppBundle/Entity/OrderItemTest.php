<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    /** @var OrderItem */
    private $orderItem;

    public function setUp()
    {
        $this->orderItem = new OrderItem();
    }

    public function testSettingOrder()
    {
        $this->orderItem->setOrder(new Order());
        $this->assertInstanceOf(Order::class, $this->orderItem->getOrder());
    }

    public function testSettingProduct()
    {
        $product = new Product();
        $product->setName($name = 'Product name');
        $product->setCode($code = 'Product code');
        $product->setPrice($price = 100);

        $this->orderItem->addProduct($product);

        $this->assertInstanceOf(Product::class, $this->orderItem->getProduct());
        $this->assertEquals($name, $this->orderItem->getProductName());
        $this->assertEquals($code, $this->orderItem->getProductCode());
        $this->assertEquals($price, $this->orderItem->getPrice());
    }

    public function testSettingQuantity()
    {
        $this->orderItem->setQuantity($quantity = 10);
        $this->assertEquals($quantity, $this->orderItem->getQuantity());
    }
}