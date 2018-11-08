<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\CharacteristicType;
use Symfony\Component\Form\Test\TypeTestCase;

class CharacteristicTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Category Name',
            'type' => 'string',
            'required' => false,
            'default' => 'default value',
            'variantsText' => 'var1,var2,var3',
            'sort' => 100,
        ];

        $form = $this->factory->create(CharacteristicType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}