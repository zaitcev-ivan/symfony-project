<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Characteristic;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CharacteristicTest extends TestCase
{
    /** @var Characteristic */
    private $characteristic;

    public function setUp()
    {
        $this->characteristic = new Characteristic();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->characteristic->getVariants());
    }

    public function testSettingName()
    {
        $this->characteristic->setName('Name');
        $this->assertEquals('Name', $this->characteristic->getName());
    }

    public function testSettingSort()
    {
        $this->characteristic->setSort(10);
        $this->assertEquals(10, $this->characteristic->getSort());
    }

    public function testSettingDefault()
    {
        $this->characteristic->setDefault('default');
        $this->assertEquals('default', $this->characteristic->getDefault());
    }

    public function testSettingRequired()
    {
        $this->characteristic->setRequired(true);
        $this->assertTrue($this->characteristic->isRequired());
    }

    public function testSettingIntegerType()
    {
        $this->characteristic->setType('integer');
        $this->assertEquals('integer', $this->characteristic->getType());
        $this->assertTrue($this->characteristic->isInteger());
        $this->assertFalse($this->characteristic->isFloat());
        $this->assertFalse($this->characteristic->isString());
    }

    public function testSettingStringType()
    {
        $this->characteristic->setType('string');
        $this->assertEquals('string', $this->characteristic->getType());
        $this->assertTrue($this->characteristic->isString());
        $this->assertFalse($this->characteristic->isFloat());
        $this->assertFalse($this->characteristic->isInteger());
    }

    public function testSettingFloatType()
    {
        $this->characteristic->setType('float');
        $this->assertEquals('float', $this->characteristic->getType());
        $this->assertTrue($this->characteristic->isFloat());
        $this->assertFalse($this->characteristic->isString());
        $this->assertFalse($this->characteristic->isInteger());
    }

    /**
     * @expectedException \DomainException
     */
    public function testSettingInvalidType()
    {
        $this->characteristic->setType('unknown type');
    }
}