<?php

namespace AppBundle\Service;

use AppBundle\Cart\Cart;
use AppBundle\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    private $cart;
    private $productRepository;

    public function __construct(Cart $cart, EntityManagerInterface $em)
    {
        $this->cart = $cart;
        $this->productRepository = $em->getRepository('AppBundle:Product');
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function add($productId): void
    {
        $product = $this->productRepository->find($productId);
        $this->cart->add(new CartItem($product));
    }

    public function set($productId, $quantity): void
    {
        $this->cart->set($productId, $quantity);
    }

    public function remove($productId): void
    {
        $this->cart->remove($productId);
    }

    public function clear(): void
    {
        $this->cart->clear();
    }
}