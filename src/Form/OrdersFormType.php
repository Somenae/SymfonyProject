<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Shipping;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressShipped', EntityType::class, [
                'class' => Address::class,  
                'choices' => $options['address'],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('billingAddress', EntityType::class, [
                'class' => Address::class,  
                'choices' => $options['address'],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('shipping', EntityType::class, [
                'class'=> Shipping::class,
                'choice_label' => 'company',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'address' => [],
        ]);
    }
}
