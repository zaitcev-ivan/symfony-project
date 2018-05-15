<?php

namespace AppBundle\Service;

use AppBundle\Dto\BrandDto;
use AppBundle\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BrandService
 * @package AppBundle\Service
 */
class BrandService
{
    private $em;
    private $brandRepository;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->brandRepository = $this->em->getRepository('AppBundle:Brand');
    }

    /**
     * @param BrandDto $dto
     */
    public function create(BrandDto $dto): void
    {
        $brand = new Brand();
        $brand->setName($dto->name);

        $this->em->persist($brand);
        $this->em->flush();
    }

    /**
     * @param integer $id
     * @param BrandDto $dto
     */
    public function edit($id, BrandDto $dto): void
    {
        $brand = $this->brandRepository->find($id);

        $brand->setName($dto->name);

        $this->em->persist($brand);
        $this->em->flush();
    }
}