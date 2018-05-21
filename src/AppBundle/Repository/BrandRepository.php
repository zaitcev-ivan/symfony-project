<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Brand;
use Doctrine\ORM\EntityRepository;

/**
 * Class BrandRepository
 * @package AppBundle\Repository
 */
class BrandRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Brand
     * @throws \DomainException
     */
    public function get($id): Brand
    {
        if(!$brand = $this->find($id)) {
            throw new \DomainException('Brand not found');
        }
        return $brand;
    }
}