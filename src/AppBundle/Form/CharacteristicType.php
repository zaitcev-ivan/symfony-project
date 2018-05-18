<?php

namespace AppBundle\Form;

use AppBundle\Dto\CharacteristicDto;
use AppBundle\Entity\Characteristic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacteristicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = array_flip(Characteristic::typesList());

        $builder
            ->add('name', TextType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите название']])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'attr'=> ['placeholder' => 'Выберите тип'],
                'choices' => $choices,
            ])
            ->add('required', CheckboxType::class, ['label' => 'Обязательное для заполенения'])
            ->add('default', TextType::class, ['label' => false, 'attr'=> ['placeholder' => 'Введите значение по умолчанию']])
            ->add('variantsText', TextareaType::class, ['label' => false, 'attr'=> ['placeholder' => 'Каждый вариант с новой строки', 'rows' => 5]])
            ->add('sort', IntegerType::class, ['label' => false, 'attr'=> ['placeholder' => 'Порядок сортировки']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacteristicDto::class,
        ]);
    }
}