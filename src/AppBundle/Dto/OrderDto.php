<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $userName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $userPhone;

    /**
     * @Assert\Length(max="255")
     */
    public $note;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $deliveryIndex;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="1000")
     */
    public $deliveryAddress;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    public $deliveryMethodId;

}