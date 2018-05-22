<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Product;
use AppBundle\Helpers\CategoryHelper;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCategoriesDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Length(min="0")
     */
    public $main;

    /**
     * @var array
     * @Assert\Type(type="array")
     */
    public $other;

    /**
     * ProductCategoriesDto constructor.
     * @param Product $product
     */
    public function __construct(Product $product = null)
    {
        if($product instanceof Product) {
            $this->main = $product->getCategory()->getId();
            $this->other = CategoryHelper::getAssignmentList($product);
        }
    }
}