<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\PasswordResetRequestType;
use Symfony\Component\Form\Test\TypeTestCase;

class PasswordResetRequestTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'user@email.ru',
        ];

        $form = $this->factory->create(PasswordResetRequestType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}