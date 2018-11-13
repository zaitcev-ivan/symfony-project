<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Product;
use AppBundle\Entity\Value;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    /** @var Product */
    private $product;

    public function setUp()
    {
        $this->product = new Product();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(\DateTime::class, $this->product->getCreatedAt());
        $this->assertInstanceOf(ArrayCollection::class, $this->product->getAssignmentsCategory());
        $this->assertInstanceOf(ArrayCollection::class, $this->product->getValues());
        $this->assertInstanceOf(ArrayCollection::class, $this->product->getPhotos());
    }

    public function testSettingPrice()
    {
        $this->product->setPrice($price = 100);
        $this->assertEquals($price, $this->product->getPrice());
    }

    public function testSettingBrand()
    {
        $this->product->setBrand($brand = new Brand());
        $this->assertEquals($brand, $this->product->getBrand());
    }

    public function testSettingCategory()
    {
        $this->product->setCategory($category = new Category());
        $this->assertEquals($category, $this->product->getCategory());
    }

    public function testSettingName()
    {
        $this->product->setName($name = 'Product name');
        $this->assertEquals($name, $this->product->getName());
    }

    public function testSettingCode()
    {
        $this->product->setCode($code = 'Product code');
        $this->assertEquals($code, $this->product->getCode());
    }

    public function testAssignCategory()
    {
        $this->product->assignCategory($category = new Category());
        $this->assertEquals($category, $this->product->getAssignmentsCategory()[0]);
    }

    public function testRemoveCategory()
    {
        $this->product->assignCategory($category = new Category());
        $this->product->removeCategory($category);
        $this->assertEquals(0, \count($this->product->getAssignmentsCategory()));
    }

    public function testRemoveAllAssignCategories()
    {
        $this->product->assignCategory($category1 = new Category());
        $this->product->assignCategory($category2 = new Category());
        $this->product->removeAllCategories();
        $this->assertEquals(0, \count($this->product->getAssignmentsCategory()));
    }

    public function testAddValue()
    {
        $this->product->addValue($value = new Value());
        $this->assertEquals($value, $this->product->getValues()[0]);
    }

    public function testRemoveValue()
    {
        $this->product->addValue($value = new Value());
        $this->product->removeValue($value);
        $this->assertEquals(0, \count($this->product->getValues()));
    }

    public function testRemoveAllValues()
    {
        $this->product->addValue($value1 = new Value());
        $this->product->addValue($value2 = new Value());
        $this->product->removeAllValues();
        $this->assertEquals(0, \count($this->product->getValues()));
    }

    public function testAddPhoto()
    {
        $this->product->addPhoto($photo = new Photo());
        $this->assertEquals($photo, $this->product->getPhotos()[0]);
    }
}