<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\LoginForm;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginFormTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            '_username' => 'User Name',
            '_password' => 'password',
        ];

        $form = $this->factory->create(LoginForm::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}