<?php

namespace AppBundle\Form;

use AppBundle\Dto\ProductCategoriesDto;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoriesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choicesCategory = [];

        /** @var Category $category */
        foreach((array)$options['categories'] as $category) {
            $id = $category->getId();
            $name = ($category->getLevel() > 0 ? str_repeat('-- ', $category->getLevel()) . ' ' : '') . $category->getName();
            $choicesCategory[$name] = $id;
        }

        $builder
            ->add('main', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Выберите основную категорию',
                'choices' => $choicesCategory,
            ])
            ->add('other', ChoiceType::class, [
                'label' => 'Выберите дополнительные категории',
                'choices' => $choicesCategory,
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCategoriesDto::class,
            'categories' => null,
        ]);
    }
}