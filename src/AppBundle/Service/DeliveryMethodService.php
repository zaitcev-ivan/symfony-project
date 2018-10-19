<?php

namespace AppBundle\Service;

use AppBundle\Dto\DeliveryMethodDto;
use AppBundle\Entity\DeliveryMethod;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryMethodService
{
    private $em;
    private $deliveryMethodRepository;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->deliveryMethodRepository = $this->em->getRepository('AppBundle:DeliveryMethod');
    }

    /**
     * @param DeliveryMethodDto $dto
     */
    public function create(DeliveryMethodDto $dto): void
    {
        $deliveryMethod = new DeliveryMethod();
        $deliveryMethod->setName($dto->name);
        $deliveryMethod->setCost($dto->cost);

        $this->em->persist($deliveryMethod);
        $this->em->flush();
    }

    /**
     * @param $id
     * @param DeliveryMethodDto $dto
     */
    public function edit($id, DeliveryMethodDto $dto): void
    {
        $deliveryMethod = $this->deliveryMethodRepository->get($id);
        $deliveryMethod->setName($dto->name);
        $deliveryMethod->setCost($dto->cost);

        $this->em->persist($deliveryMethod);
        $this->em->flush();
    }
}