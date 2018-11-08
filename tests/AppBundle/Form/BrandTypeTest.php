<?php

namespace Tests\AppBundle\Form;

use AppBundle\Dto\BrandDto;
use AppBundle\Form\BrandType;
use Symfony\Component\Form\Test\TypeTestCase;

class BrandTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $brandName = 'Brand';
        $formData = [
            'name' => $brandName,
        ];

        $form = $this->factory->create(BrandType::class);

        $object = new BrandDto();
        $object->name = $brandName;

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}