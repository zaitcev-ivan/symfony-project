<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContactCreateDto
{
    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="3000")
     */
    public $message;
}