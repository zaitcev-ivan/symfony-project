<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Characteristic;
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
     * @Assert\Valid()
     * @var ProductCategoriesDto
     */
    public $category;

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

    /**
     * @var ValueDto[]
     */
    public $values;

    public function __construct(Product $product = null, array $characteristicList = [])
    {
        if($product instanceof Product) {
            $this->name = $product->getName();
            $this->code = $product->getCode();
            $this->price = $product->getPrice();
            $this->category = new ProductCategoriesDto($product);
            $this->brandId = $product->getBrand()->getId();
        }
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueDto($characteristic);
        }, $characteristicList);
    }
}