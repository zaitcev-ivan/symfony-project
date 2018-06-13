<?php


namespace AppBundle\Dto;


use AppBundle\Entity\Characteristic;
use Symfony\Component\Validator\Constraints as Assert;

class CharacteristicDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @Assert\NotBlank()
     */
    public $type;

    /**
     * @Assert\Type(type="boolean")
     */
    public $required;

    public $default;

    public $variantsText;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Length(min="0")
     */
    public $sort;

    public function __construct(Characteristic $characteristic = null, $sort = 0)
    {
        if($characteristic instanceof Characteristic) {
            $this->name = $characteristic->getName();
            $this->type = $characteristic->getType();
            $this->required = $characteristic->getRequired();
            $this->default = $characteristic->getDefault();
            $this->variantsText = $characteristic->getVariants() ? implode(PHP_EOL, $characteristic->getVariants()) : "";
            $this->sort = $characteristic->getSort();
        }
        else {
            $this->sort = $sort;
        }
    }

    public function getVariants()
    {
        return trim($this->variantsText) ? preg_split('#\s+#i', $this->variantsText) : 0;
    }
}