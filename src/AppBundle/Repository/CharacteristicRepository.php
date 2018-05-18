<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CharacteristicRepository extends EntityRepository
{
    public function getMaxSortValue()
    {
        $query = $this->createQueryBuilder('c')
            ->select('MAX(c.sort) AS max_sort')
            ->orderBy('max_sort', 'DESC')
        ;

        $result = $query->getQuery()->getResult();
        return $result[0]['max_sort'] ?: 0;
    }
}