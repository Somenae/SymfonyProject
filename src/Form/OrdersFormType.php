<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Orders;
use App\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressShipped', EntityType::class, [
                'class' => Address::class,
                'choice_label' => 'address',
            ])
            ->add('billingAddress', EntityType::class, [
                'class' => Address::class,
                'choice_label' => 'address',
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => 'type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
