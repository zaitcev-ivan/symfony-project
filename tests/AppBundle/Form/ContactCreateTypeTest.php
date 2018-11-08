<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\ContactCreateType;
use Symfony\Component\Form\Test\TypeTestCase;

class ContactCreateTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Contact Name',
            'email' => 'contact@email.ru',
            'subject' => 'subject',
            'message' => 'message',
        ];

        $form = $this->factory->create(ContactCreateType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}