<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Category name',
            'parentId' => 10,
        ];

        $form = $this->factory->create(CategoryType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}