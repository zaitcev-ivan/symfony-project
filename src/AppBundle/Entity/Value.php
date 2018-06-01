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
    public function __construct(Characteristic $characteristic, $value = '')
    {
        $this->characteristic = $characteristic;
        $this->value = $value;
    }

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="values")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Characteristic", inversedBy="values")
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
}