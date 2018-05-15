<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        foreach($objectSet as $object) {
            $manager->persist($object);
        }
        $manager->flush();

        $this->loadBrands($manager);
    }

    private function loadBrands(ObjectManager $manager)
    {
        foreach($this->getBrandData() as $index => $name) {
            $brand = new Brand();
            $brand->setName($name);

            $manager->persist($brand);
        }

        $manager->flush();
    }

    private function getBrandData(): array
    {
        return [
            'ACNE',
            'GRUNE ERDE',
            'ALBIRO',
            'RONHILL',
            'ODDMOLLY',
            'BOUDESTIJN',
            'ROSCH CREATIVE CULTURE',
        ];
    }
}