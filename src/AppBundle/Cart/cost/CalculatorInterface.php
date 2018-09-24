<?php

namespace AppBundle\Cart\cost;

use AppBundle\Entity\CartItem;

interface CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return integer
     */
    public function  getCost(array $items): int;
}