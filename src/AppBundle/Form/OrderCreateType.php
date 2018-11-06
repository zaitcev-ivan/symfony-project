<?php

namespace AppBundle\Form;

use AppBundle\Dto\OrderDto;
use AppBundle\Entity\DeliveryMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choicesDeliveryMethodList = [];

        /** @var DeliveryMethod $deliveryMethod */
        foreach ((array)$options['deliveryMethodList'] as $deliveryMethod) {
            $choicesDeliveryMethodList[$deliveryMethod->getName()] = $deliveryMethod->getId();
        }

        $builder
            ->add('userName', TextType::class,['label' => false, 'attr'=> ['placeholder' => 'Введите имя']])
            ->add('userPhone', TextType::class,['label' => false, 'attr'=> ['placeholder' => 'Введите телефон']])
            ->add('note', TextareaType::class,['label' => false, 'attr'=> ['placeholder' => 'Введите комментарий', 'rows' => 5]])
            ->add('deliveryIndex', TextType::class,['label' => false, 'attr'=> ['placeholder' => 'Введите почтовый индекс']])
            ->add('deliveryAddress', TextType::class,['label' => false, 'attr'=> ['placeholder' => 'Введите адрес']])
            ->add('deliveryMethodId', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Выберите метод доставки',
                'choices' => $choicesDeliveryMethodList,
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
            'data_class' => OrderDto::class,
            'deliveryMethodList' => null,
        ]);
    }
}