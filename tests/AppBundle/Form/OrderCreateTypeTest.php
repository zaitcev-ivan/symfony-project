<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\OrderCreateType;
use Symfony\Component\Form\Test\TypeTestCase;

class OrderCreateTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'userName' => 'User Name',
            'userPhone' => 'User Phone',
            'note' => 'note',
            'deliveryIndex' => 'delivery index',
            'deliveryAddress' => 'delivery address',
            'deliveryMethodId' => 'delivery method',
        ];

        $form = $this->factory->create(OrderCreateType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}