<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Dto\CharacteristicDto;
use AppBundle\Dto\PhotoDto;
use AppBundle\Dto\ProductCategoriesDto;
use AppBundle\Dto\ProductDto;
use AppBundle\Dto\ValueDto;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use AppBundle\Entity\Characteristic;
use AppBundle\Entity\DeliveryMethod;
use AppBundle\Service\CharacteristicService;
use AppBundle\Service\ProductService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadFixtures extends Fixture
{
    private $characteristicService;
    private $productService;
    
    public function __construct
    (
        CharacteristicService $characteristicService,
        ProductService $productService
    )
    {
        $this->characteristicService = $characteristicService;
        $this->productService = $productService;
    }

    /**
     * @param ObjectManager $manager
     * @throws \DomainException
     * @throws \Nelmio\Alice\Throwable\LoadingThrowable
     * @throws \Exception
     */
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
        
        $this->loadDeliveryMethods($manager);
        
        $this->loadCharacteristic();
        
        $this->loadProducts($manager);
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
    
    private function loadDeliveryMethods(ObjectManager $manager)
    {
        foreach ($this->getDeliveryMethods() as $deliveryMethod) {
            $method = new DeliveryMethod();
            $method->setName($deliveryMethod['name']);
            $method->setCost($deliveryMethod['price']);
            
            $manager->persist($method);
        }
        $manager->flush();
    }
    
    private function getDeliveryMethods(): array
    {
        return [
            ['name' => 'Почта России', 'price' => 300],
            ['name' => 'СДЭК', 'price' => 280],
            ['name' => 'DHL', 'price' => 320],
            ['name' => 'Boxberry', 'price' => 250],
        ];
    }
    
    private function loadCharacteristic()
    {
        foreach ($this->getCharacteristic() as $i => $item) {
            $dto = new CharacteristicDto();
            $dto->name = $item['name'];
            $dto->type = $item['type'];
            $dto->required = $item['required'];
            $dto->variantsText = $item['variantsText'];
            $dto->sort = $i;
            
            $this->characteristicService->create($dto);
        }
    }
    
    private function getCharacteristic(): array
    {
        return [
            [
                'name' => 'Цвет',
                'type' => 'string',
                'required' => true,
                'variantsText' => "Белый\nСиний\nКрасный\nЖелтый"
            ],
            [
                'name' => 'Размер',
                'type' => 'integer',
                'required' => true,
                'variantsText' => "42\n43\n44\n45"
            ],
            [
                'name' => 'Материал',
                'type' => 'string',
                'required' => false,
                'variantsText' => "Хлопок\nСинтетика\nШелк"
            ],
        ];
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function loadProducts(ObjectManager $manager)
    {
        $categories = $manager->getRepository('AppBundle:Category')->categoryList();
        $brands = $manager->getRepository('AppBundle:Brand')->findAll();
        $characteristicList = $manager->getRepository('AppBundle:Characteristic')->findAll();
        
        for ($count = 0; $count < 20; $count++) {
            $productName = 'Товар ' . $count;
            $code = '252345' . $count;
            $categoryId = $categories[random_int(0, \count($categories)-1)];
            $brandId = $brands[random_int(0, \count($brands)-1)];
            $price = random_int(10, 20) * 100;
            $productDto = $this->getProduct($productName, $code, $categoryId, $brandId, $price, $characteristicList);
            $this->productService->create($productDto);
        }
    }

    /**
     * @param $productName
     * @param $code
     * @param $categoryId
     * @param $brandId
     * @param $price
     * @param Characteristic[] $characteristicList
     * @return ProductDto
     * @throws \Exception
     */
    private function getProduct($productName, $code, $categoryId, $brandId, $price, array $characteristicList): ProductDto
    {
        $productDto = new ProductDto();
        $productDto->name = $productName;
        $productDto->code = $code;
        $productDto->category = new ProductCategoriesDto();
        $productDto->category->main = $categoryId;
        $productDto->category->other = [];
        $productDto->brandId = $brandId;
        $productDto->price = $price;
        
        foreach ($characteristicList as $item) {
            $values = (array) $item->getVariants();
            $valueDto =  new ValueDto($item);
            $valueDto->value = $values[random_int(0, \count($values)-1)];
            $productDto->values[] = $valueDto;
        }
        
        $productDto->photo = new PhotoDto();
        $productDto->photo->files = $this->getProductPhotos();
        
        return $productDto;
    }

    /**
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    private function getProductPhotos(): array
    {
        $files = [
            'product7.jpg',
            'product8.jpg',
            'product9.jpg',
            'product10.jpg',
            'product11.jpg',
            'product12.jpg',
        ];
        shuffle($files);
        $arrayFiles = [];
        foreach ($files as $file) {
            copy(__DIR__ . '/../../../../web/imageFixtures/' . $file, __DIR__ . '/../../../../web/imageFixtures/upload/' . $file);
            $arrayFiles[] = new UploadedFile(__DIR__ . '/../../../../web/imageFixtures/upload/' . $file, $file, null, null, null, true);
        }
        
        return $arrayFiles;
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