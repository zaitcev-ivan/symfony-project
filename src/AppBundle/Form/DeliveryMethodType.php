<?php

namespace AppBundle\Form;

use AppBundle\Dto\DeliveryMethodDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DeliveryMethodType
 * @package AppBundle\Form
 */
class DeliveryMethodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['label' => false, 'attr'=> ['placeholder' => 'Название метода доставки']])
            ->add('cost', IntegerType::class,['label' => false, 'attr'=> ['placeholder' => 'Стоимость доставки']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeliveryMethodDto::class,
        ]);
    }
}