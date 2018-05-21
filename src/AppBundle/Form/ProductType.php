<?php

namespace AppBundle\Form;

use AppBundle\Dto\ProductDto;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choicesCategory = [];
        $choicesBrand = [];

        /** @var Category $category */
        foreach((array)$options['categories'] as $category) {
            $id = $category->getId();
            $name = ($category->getLevel() > 0 ? str_repeat('-- ', $category->getLevel()) . ' ' : '') . $category->getName();
            $choicesCategory[$name] = $id;
        }

        /** @var Brand $brand */
        foreach((array)$options['brands'] as $brand) {
            $id = $brand->getId();
            $name = $brand->getName();
            $choicesBrand[$name] = $id;
        }

        $builder
            ->add('name', TextType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите название']])
            ->add('code', TextType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите код']])
            ->add('categoryId', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Выберите категорию',
                'choices' => $choicesCategory,
            ])
            ->add('brandId', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Выберите бренд',
                'choices' => $choicesBrand,
            ])
            ->add('price', IntegerType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите цену']])

        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductDto::class,
            'categories' => null,
            'brands' => null,
        ]);
    }
}