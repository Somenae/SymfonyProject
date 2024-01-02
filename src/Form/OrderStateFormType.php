<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\OrderState;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Shipping;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderState', EntityType::class, [
                'class' => OrderState::class,
                'choice_label' => 'label',
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Enregistrer le changement'
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
