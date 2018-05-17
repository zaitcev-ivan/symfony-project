<?php

namespace AppBundle\Service;

use AppBundle\Dto\CategoryDto;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $em;
    private $categoryRepository;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->categoryRepository = $this->em->getRepository('AppBundle:Category');
    }

    /**
     * Создание категории
     * @param CategoryDto $dto
     * @throws \DomainException
     */
    public function create(CategoryDto $dto): void
    {
        $category = new Category();
        $category->setName($dto->name);

        $this->setParentCategory($category, $dto->parentId);

        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * Редактирование категории
     * @param Category $category
     * @param CategoryDto $dto
     * @throws \DomainException
     */
    public function edit(Category $category, CategoryDto $dto): void
    {
        $category->setName($dto->name);

        $parentId = $category->getParent() ? $category->getParent()->getId() : null;

        if ($parentId !== $dto->parentId) {
            $this->setParentCategory($category, $dto->parentId);
        }

        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * Удаление категории
     * @param Category $category
     * @throws \RuntimeException
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->removeFromTree($category);
        $this->em->clear();
    }

    /**
     * Переместить категорию выше
     * @param Category $category
     * @throws \RuntimeException
     */
    public function moveUp(Category $category): void
    {
        $this->categoryRepository->moveUp($category);
    }

    /**
     * Переместить категорию ниже
     * @param Category $category
     * @throws \RuntimeException
     */
    public function moveDown(Category $category): void
    {
        $this->categoryRepository->moveDown($category);
    }

    /**
     * @param Category $category
     * @param $parentId
     * @throws \DomainException
     */
    private function setParentCategory(Category $category, $parentId): void
    {
        if(null !== $parentId) {
            $parent = $this->categoryRepository->get($parentId);
            $category->setParent($parent);
        }
        else {
            $this->setRootCategory($category);
        }
    }

    /**
     * @param Category $category
     * @throws \DomainException
     */
    private function setRootCategory(Category $category): void
    {
        $rootCategory = $this->categoryRepository->getRootCategory();
        $category->setParent($rootCategory);
    }
}