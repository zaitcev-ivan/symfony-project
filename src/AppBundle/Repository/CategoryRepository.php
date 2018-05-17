<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    /**
     * @param $id
     * @return Category
     * @throws \DomainException
     */
    public function get($id): Category
    {
        if(!$category = $this->find($id)) {
            throw new \DomainException('Category not found');
        }
        return $category;
    }

    /**
     * @return Category
     * @throws \DomainException
     */
    public function getRootCategory(): Category
    {
        if(!$category = $this->findOneBy(['name' => Category::getRootCategoryName()])) {
            throw new \DomainException('Root category not found');
        }
        return $category;
    }

    /**
     * @param array $excludedIds
     * @return mixed
     */
    public function categoryList(array $excludedIds = []) {

        $query = $this->createQueryBuilder('category');
        $query->andWhere('category.level > 0')
            ->orderBy('category.root, category.left', 'ASC')
        ;

        if($excludedIds) {
            $query->andWhere('category.id NOT IN (' . implode(',', $excludedIds) . ')');
        }

        return $query->getQuery()->execute();
    }
}