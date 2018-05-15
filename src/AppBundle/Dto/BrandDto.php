<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Brand;
use Symfony\Component\Validator\Constraints as Assert;

class BrandDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    public function __construct(Brand $brand = null)
    {
        if($brand instanceof Brand) {
            $this->name = $brand->getName();
        }
    }
}