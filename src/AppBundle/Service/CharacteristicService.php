<?php

namespace AppBundle\Service;

use AppBundle\Dto\CharacteristicDto;
use AppBundle\Entity\Characteristic;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CharacteristicService
 * @package AppBundle\Service
 */
class CharacteristicService
{
    private $em;
    private $characteristicRepository;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->characteristicRepository = $this->em->getRepository('AppBundle:Characteristic');
    }

    /**
     * @param CharacteristicDto $dto
     * @throws \DomainException
     */
    public function create(CharacteristicDto $dto): void
    {
        $characteristic = new Characteristic();
        $characteristic->setName($dto->name);
        $characteristic->setType($dto->type);
        $characteristic->setRequired($dto->required);
        $characteristic->setDefault($dto->default);
        $characteristic->setVariants($dto->getVariants());
        $characteristic->setSort($dto->sort);

        $this->em->persist($characteristic);
        $this->em->flush();
    }

    /**
     * @param Characteristic $characteristic
     * @param CharacteristicDto $dto
     * @throws \DomainException
     */
    public function edit(Characteristic $characteristic, CharacteristicDto $dto): void
    {
        $characteristic->setName($dto->name);
        $characteristic->setType($dto->type);
        $characteristic->setRequired($dto->required);
        $characteristic->setDefault($dto->default);
        $characteristic->setVariants($dto->getVariants());
        $characteristic->setSort($dto->sort);

        $this->em->persist($characteristic);
        $this->em->flush();
    }

    /**
     * @param Characteristic $characteristic
     */
    public function delete(Characteristic $characteristic): void
    {
        $this->em->remove($characteristic);
        $this->em->flush();
    }
}