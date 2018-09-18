<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findLastProducts($count = 6)
    {
        return $this->createQueryBuilder('product')
            ->orderBy('product.createdAt')
            ->setMaxResults($count)
            ->getQuery()
            ->execute();
    }

    public function findAllByCategory($categoryId)
    {
        return $this->createQueryBuilder('product')
            ->andWhere('product.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('product.createdAt')
            ->getQuery()
            ->execute();
    }
}