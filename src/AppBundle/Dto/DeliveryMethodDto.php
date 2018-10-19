<?php

namespace AppBundle\Dto;

use AppBundle\Entity\DeliveryMethod;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DeliveryMethodDto
 * @package AppBundle\Dto
 */
class DeliveryMethodDto
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Length(min="0")
     */
    public $cost;

    /**
     * DeliveryMethodDto constructor.
     * @param DeliveryMethod|null $deliveryMethod
     */
    public function __construct(DeliveryMethod $deliveryMethod = null)
    {
        if ($deliveryMethod instanceof DeliveryMethod) {
            $this->name = $deliveryMethod->getName();
            $this->cost = $deliveryMethod->getCost();
        }
    }
}