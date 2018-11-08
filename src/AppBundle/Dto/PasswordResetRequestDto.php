<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetRequestDto
{
    /**
     * @Assert\Email()
     */
    public $email;
}