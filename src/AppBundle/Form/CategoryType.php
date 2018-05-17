<?php

namespace AppBundle\Form;

use AppBundle\Dto\CategoryDto;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        $choices['Нет родительской категории'] = null;

        /** @var Category $category */
        foreach((array)$options['categories'] as $category) {
            $id = $category->getId();
            $name = ($category->getLevel() > 0 ? str_repeat('-- ', $category->getLevel()) . ' ' : '') . $category->getName();
            $choices[$name] = $id;
        }

        $builder
            ->add('name', TextType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите название категории']])
            ->add('parentId', ChoiceType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Родительская категория'],
                'choices' => $choices,
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
            'data_class' => CategoryDto::class,
            'categories' => null,
        ]);
    }
}