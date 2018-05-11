<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Поиск сущности User по email
     * @param $email
     * @return User
     */
    public function findOneByEmail($email): User
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->execute();
    }

    /**
     * Поиск сущности User по reset_token
     * @param $token
     * @return User
     */
    public function findOneByResetToken($token): User
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.reset_token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->execute();
    }
}