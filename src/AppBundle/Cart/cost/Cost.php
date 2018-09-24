<?php

namespace AppBundle\Cart\cost;

use AppBundle\Entity\CartItem;

class Cost implements CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return integer
     */
    public function getCost(array $items): int
    {
        $cost = 0;
        foreach ($items as $item) {
            $cost += $item->getCost();
        }
        return $cost;
    }
}