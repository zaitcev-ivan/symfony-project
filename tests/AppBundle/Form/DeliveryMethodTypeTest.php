<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\DeliveryMethodType;
use Symfony\Component\Form\Test\TypeTestCase;

class DeliveryMethodTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Delivery Method Name',
            'cost' => '150',
        ];

        $form = $this->factory->create(DeliveryMethodType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}