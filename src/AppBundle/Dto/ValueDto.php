<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Characteristic;
use AppBundle\Entity\Value;

class ValueDto
{
    public $value;
    public $label;

    private $characteristic;

    public function __construct(Characteristic $characteristic, Value $value = null)
    {
        if($value instanceof Value) {
            $this->value = $value->getValue();
        }
        $this->label = $characteristic->getName();
        $this->characteristic = $characteristic;
    }

    public function variantsList(): array
    {
        return $this->characteristic ? array_combine($this->characteristic->getVariants(), $this->characteristic->getVariants()) : [];
    }

    public function getCharacteristic(): Characteristic
    {
        return $this->characteristic;
    }
}