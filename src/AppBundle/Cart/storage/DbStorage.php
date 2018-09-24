<?php

namespace AppBundle\Cart\storage;

use AppBundle\Entity\CartItem;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DbStorage implements StorageInterface
{
    /** @var User */
    private $user;
    private $em;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->em = $em;
    }

    /**
     * @return CartItem[]
     */
    public function load(): array
    {
        $cartItemRepository = $this->em->getRepository('AppBundle:CartItem');
        return $cartItemRepository->findAllByUserId($this->user->getId());
    }

    /**
     * @param CartItem[] $items
     */
    public function save(array $items): void
    {
        $cartItemRepository = $this->em->getRepository('AppBundle:CartItem');
        $cartItems = $cartItemRepository->findAllByUserId($this->user->getId());
        foreach ($cartItems as $oldItem) {
            $this->em->remove($oldItem);
        }
        $this->em->flush();

        foreach ($items as $item) {
            $item->setUser($this->user);
            $this->em->persist($item);
        }
        $this->em->flush();
    }
}