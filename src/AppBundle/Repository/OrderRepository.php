<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Order;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderRepository extends EntityRepository
{
    /**
     * @param $orderId
     * @param $userId
     * @return Order|object
     */
    public function getByUser($orderId, $userId)
    {
        if ($order = $this->findONeBy(['id' => $orderId, 'user' => $userId])) {
            return $order;
        }
        throw new NotFoundHttpException();
    }

    /**
     * @param $userId
     * @return null|array|Order[]
     */
    public function getAllByUser($userId)
    {
        return $this->findBy(['user' => $userId]);
    }
}