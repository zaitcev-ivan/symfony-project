<?php

namespace AppBundle\Repository;
use AppBundle\Entity\CartItem;

/**
 * CartItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartItemRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $userId
     * @return CartItem[]
     */
    public function findAllByUserId($userId)
    {
        return $this->createQueryBuilder('cart_item')
            ->andWhere('cart_item.user = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->execute();
    }
}
