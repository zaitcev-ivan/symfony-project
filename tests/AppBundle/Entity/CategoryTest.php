<?php

namespace Tests\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /** @var Category */
    private $category;

    public function setUp()
    {
        $this->category = new Category();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->category->getProducts());
    }

    public function testSettingRootName()
    {
        $this->category->setRootName();
        $this->assertSame(Category::getRootCategoryName(), $this->category->getName());
        $this->assertFalse($this->category->isEqualRootCategory('Category name'));
    }

    /**
     * @expectedException \DomainException
     */
    public function testErrorSettingRootName()
    {
        $this->category->setName(Category::getRootCategoryName());
    }

    public function testSettingName()
    {
        $this->category->setName('Category name');
        $this->assertSame('Category name', $this->category->getName());
    }
}