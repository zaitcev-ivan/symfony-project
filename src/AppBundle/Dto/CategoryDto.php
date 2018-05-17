<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @Assert\Type(type="integer")
     */
    public $parentId;

    public function __construct(Category $category = null)
    {
        if($category instanceof Category) {
            $this->name = $category->getName();
            $this->parentId = $category->getParent() ? $category->getParent()->getId() : null;
        }
    }
}