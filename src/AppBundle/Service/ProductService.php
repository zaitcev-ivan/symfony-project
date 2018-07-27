<?php

namespace AppBundle\Service;

use AppBundle\Dto\ProductDto;
use AppBundle\Dto\ValueDto;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Product;
use AppBundle\Entity\Value;
use AppBundle\Repository\BrandRepository;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductService
 * @package AppBundle\Service
 */
class ProductService
{
    private $em;
    /** @var ProductRepository */
    private $productRepository;
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var BrandRepository  */
    private $brandRepository;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->productRepository = $this->em->getRepository('AppBundle:Product');
        $this->categoryRepository = $this->em->getRepository('AppBundle:Category');
        $this->brandRepository = $this->em->getRepository('AppBundle:Brand');
    }

    /**
     * @param ProductDto $dto
     * @throws \DomainException
     */
    public function create(ProductDto $dto): void
    {
        $category = $this->categoryRepository->get($dto->category->main);
        $brand = $this->brandRepository->get($dto->brandId);

        $product = new Product();
        $product->setName($dto->name);
        $product->setCode($dto->code);
        $product->setPrice($dto->price);
        $product->setCategory($category);
        $product->setBrand($brand);

        foreach($dto->category->other as $otherCategoryId) {
            $category = $this->categoryRepository->get($otherCategoryId);
            $product->assignCategory($category);
        }

        /** @var ValueDto $valueDto */
        foreach($dto->values as $valueDto) {
            if(!empty($valueDto->value)) {
                $value = new Value();
                $value->setCharacteristic($valueDto->getCharacteristic());
                $value->setProduct($product);
                $value->setValue($valueDto->value);
                $product->addValue($value);
                $this->em->persist($value);
            }
        }

        foreach($dto->photo->files as $i => $file) {
            $photo = new Photo();
            $photo->setFile($file);
            $photo->setSort($i);
            $photo->setProduct($product);
            $product->addPhoto($photo);
            $this->em->persist($photo);
        }

        $this->em->persist($product);
        $this->em->flush();

    }

    /**
     * @param Product $product
     * @param ProductDto $dto
     * @throws \DomainException
     */
    public function edit(Product $product, ProductDto $dto): void
    {
        $category = $this->categoryRepository->get($dto->category->main);
        $brand = $this->brandRepository->get($dto->brandId);

        $product->setName($dto->name);
        $product->setCode($dto->code);
        $product->setPrice($dto->price);
        $product->setCategory($category);
        $product->setBrand($brand);

        $product->removeAllCategories();
        foreach($dto->category->other as $otherCategoryId) {
            $category = $this->categoryRepository->get($otherCategoryId);
            $product->assignCategory($category);
        }

        $product->removeAllValues();
        /** @var ValueDto $valueDto */
        foreach($dto->values as $valueDto) {
            if(!empty($valueDto->value)) {
                $value = new Value();
                $value->setCharacteristic($valueDto->getCharacteristic());
                $value->setProduct($product);
                $value->setValue($valueDto->value);
                $product->addValue($value);
                $this->em->persist($value);
            }
        }

        $maxSortValue = $product->getMaxSortValue();
        foreach($dto->photo->files as $file) {
            $photo = new Photo();
            $photo->setFile($file);
            $photo->setSort($maxSortValue);
            $photo->setProduct($product);
            $product->addPhoto($photo);
            $this->em->persist($photo);
            $maxSortValue++;
        }

        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * @param Product $product
     */
    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    /**
     * Удаление фотографии товара
     * @param Product $product
     * @param integer $photoId
     */
    public function deletePhoto(Product $product, $photoId): void
    {
        $product->removePhoto($photoId);
        $product->updateSortPhotos();
        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * Перемещение фото вверх на 1 позицию
     * @param Product $product
     * @param integer $photoId
     */
    public function movePhotoUp(Product $product, $photoId): void
    {
        $product->movePhotoUp($photoId);
        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * Перемещение фото вниз на 1 позицию
     * @param Product $product
     * @param integer $photoId
     */
    public function movePhotoDown(Product $product, $photoId): void
    {
        $product->movePhotoDown($photoId);
        $this->em->persist($product);
        $this->em->flush();
    }
}