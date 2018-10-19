<?php

namespace AppBundle\Repository;

use AppBundle\Entity\DeliveryMethod;
use Doctrine\ORM\EntityRepository;

class DeliveryMethodRepository extends EntityRepository
{
    /**
     * @param $id
     * @return DeliveryMethod|object
     */
    public function get($id): DeliveryMethod
    {
        if (!$deliveryMethod = $this->find($id)) {
            throw new \DomainException('Delivery method not found');
        }
        return $deliveryMethod;
    }
}