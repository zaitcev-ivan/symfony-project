<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    /**
     * @var CartItem
     */
    private $cartItem;

    /** @var Product */
    private $product;

    public function setUp()
    {
        $this->product = new Product();
        $this->cartItem = new CartItem($this->product);
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Product::class, $this->cartItem->getProduct());
        $this->assertSame(1, $this->cartItem->getQuantity());
    }

    public function testSettingQuantity()
    {
        $this->cartItem->setQuantity(5);
        $this->assertSame(5, $this->cartItem->getQuantity());
    }

    public function testGettingPrice()
    {
        $this->product->setPrice(100);
        $this->assertSame(100, $this->cartItem->getPrice());
    }

    public function testGettingCost()
    {
        $this->product->setPrice(100);
        $this->cartItem->setQuantity(5);
        $this->assertSame(500, $this->cartItem->getCost());
    }

    public function testPlus()
    {
        $this->cartItem->setQuantity(1);
        $this->cartItem = $this->cartItem->plus(2);
        $this->assertSame(3, $this->cartItem->getQuantity());
    }

    public function testChangeQuantity()
    {
        $this->cartItem->setQuantity(1);
        $this->cartItem = $this->cartItem->changeQuantity(10);
        $this->assertSame(10, $this->cartItem->getQuantity());
    }

    public function testSettingUser()
    {
        $this->cartItem->setUser(new User());
        $this->assertInstanceOf(User::class, $this->cartItem->getUser());
    }
}