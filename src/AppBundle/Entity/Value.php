<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Value
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="value")
 */
class Value
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="values")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Characteristic
     *
     * @ORM\ManyToOne(targetEntity="Characteristic", inversedBy="values")
     * @ORM\JoinColumn(nullable=false)
     */
    private $characteristic;

    /**
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return Characteristic
     */
    public function getCharacteristic(): Characteristic
    {
        return $this->characteristic;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isForCharacteristic($id): bool
    {
        return $id === $this->characteristic->getId();
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @param Characteristic $characteristic
     */
    public function setCharacteristic(Characteristic $characteristic): void
    {
        $this->characteristic = $characteristic;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}