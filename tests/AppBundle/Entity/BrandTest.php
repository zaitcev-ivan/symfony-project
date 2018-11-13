<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Brand;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BrandTest extends TestCase
{
    /** @var Brand */
    private $brand;

    public function setUp()
    {
        $this->brand = new Brand();
    }

    public function testSettingName()
    {
        $this->brand->setName($brandName = 'Brand name');
        $this->assertSame($brandName, $this->brand->getName());
    }

    public function testSettingProducts()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->brand->getProducts());
    }
}