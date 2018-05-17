<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
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

        $this->loadCategories($manager);
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

    /**
     * Генерация категорий
     * @param ObjectManager $manager
     * @throws \DomainException
     */
    private function loadCategories(ObjectManager $manager)
    {
        $categoryRepository = $manager->getRepository('AppBundle:Category');

        if(!$rootCategory = $categoryRepository->findOneBy(['name' => Category::getRootCategoryName()])) {
            $rootCategory = new Category();
            $rootCategory->setRootName();
            $manager->persist($rootCategory);
            $manager->flush();
        }

        $sportsWear = new Category();
        $sportsWear->setName('SPORTSWEAR');

        $nike = new Category();
        $nike->setName('NIKE');

        $underArmour = new Category();
        $underArmour->setName('UNDER ARMOUR');

        $adidas = new Category();
        $adidas->setName('ADIDAS');

        $puma = new Category();
        $puma->setName('PUMA');

        $asics = new Category();
        $asics->setName('ASICS');

        $kids = new Category();
        $kids->setName('KIDS');

        $fashion = new Category();
        $fashion->setName('FASHION');

        $houseHolds = new Category();
        $houseHolds->setName('HOUSEHOLDS');

        $clothing = new Category();
        $clothing->setName('CLOTHING');

        $categoryRepository->persistAsFirstChildOf($sportsWear, $rootCategory);
        $categoryRepository->persistAsFirstChildOf($nike, $sportsWear);
        $categoryRepository->persistAsLastChildOf($underArmour, $sportsWear);
        $categoryRepository->persistAsLastChildOf($adidas, $sportsWear);
        $categoryRepository->persistAsLastChildOf($puma, $sportsWear);
        $categoryRepository->persistAsLastChildOf($asics, $sportsWear);

        $categoryRepository->persistAsLastChildOf($kids, $rootCategory);
        $categoryRepository->persistAsLastChildOf($fashion, $rootCategory);
        $categoryRepository->persistAsLastChildOf($houseHolds, $rootCategory);
        $categoryRepository->persistAsLastChildOf($clothing, $rootCategory);

        $manager->flush();
    }
}