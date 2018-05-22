<?php

namespace AppBundle\Service;

use AppBundle\Dto\ProductDto;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductService
 * @package AppBundle\Service
 */
class ProductService
{
    private $em;
    private $productRepository;
    private $categoryRepository;
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

}