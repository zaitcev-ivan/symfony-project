<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\OrderRejectType;
use Symfony\Component\Form\Test\TypeTestCase;

class OrderRejectTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'reason' => 'Reject reason',
        ];

        $form = $this->factory->create(OrderRejectType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}