<?php

namespace AppBundle\Cart;

use AppBundle\Cart\cost\CalculatorInterface;
use AppBundle\Cart\storage\StorageInterface;
use AppBundle\Entity\CartItem;

class Cart
{
    private $storage;
    private $calculator;

    /**
     * @var CartItem[]
     */
    private $items;

    public function __construct(StorageInterface $storage, CalculatorInterface $calculator)
    {
        $this->storage = $storage;
        $this->calculator = $calculator;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        $this->loadItems();
        return $this->items;
    }

    public function getAmount(): int
    {
        $this->loadItems();
        return count($this->items);
    }

    public function getCost(): int
    {
        $this->loadItems();
        return $this->calculator->getCost($this->items);
    }

    public function add(CartItem $item): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProduct()->getId() === $item->getProduct()->getId()) {
                $this->items[$i] = $current->plus($item->getQuantity());
                $this->saveItems();
                return;
            }
        }
        $this->items[] = $item;
        $this->saveItems();
    }

    public function set($productId, $quantity): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProduct()->getId() === (int) $productId) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function remove($productId): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProduct()->getId() === (int) $productId) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function clear(): void
    {
        $this->items = [];
        $this->saveItems();
    }

    private function loadItems(): void
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems(): void
    {
        $this->storage->save($this->items);
    }
}