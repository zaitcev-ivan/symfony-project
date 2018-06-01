<?php

namespace AppBundle\Form;

use AppBundle\Dto\ValueDto;
use AppBundle\Entity\Characteristic;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValuesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {

            /** @var ValueDto $value */
            $value = $event->getData();
            $form = $event->getForm();

            $characteristic = $value->getCharacteristic();

            $constraints = array_filter([
                $characteristic->isRequired() ? new NotBlank() : false,
            ]);

            if($characteristic->isInteger()) {

                $form->add('value', IntegerType::class, [
                    'label' => $value->label,
                    'attr' => ['placeholder' => 'Введите значение'],
                    'constraints' => $constraints,
                ]);
            }
            elseif($characteristic->isFloat()) {
                $form->add('value', NumberType::class, [
                    'label' => $value->label,
                    'attr' => ['placeholder' => 'Введите значение'],
                    'constraints' => $constraints,
                ]);
            }
            else {
                $form->add('value', TextType::class, [
                    'label' => $value->label,
                    'attr' => ['placeholder' => 'Введите значение'],
                    'constraints' => $constraints,
                ]);
            }




        });
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ValueDto::class,
            'characteristic' => null,
        ]);
    }
}