<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductDto
 * @package AppBundle\Dto
 */
class ProductDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    public $categoryId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    public $brandId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Length(min="0")
     */
    public $price;

    public function __construct(Product $product = null)
    {
        if($product instanceof Product) {
            $this->name = $product->getName();
            $this->code = $product->getCode();
            $this->price = $product->getPrice();
            $this->categoryId = $product->getCategory()->getId();
            $this->brandId = $product->getBrand()->getId();
        }
    }
}