<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;



class AdminUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', null, ['label' => 'Lastname: *', 'required' => true,])
            ->add('firstname', null, ['label' => 'Firstname: *', 'required' => true,])
            ->add('email', EmailType::class, ['label' => 'E-mail: *', 'required' => true,])
            ->add('address', TextType::class, [
                'mapped' => false, 'required' => true, 'label' => 'Address: *',
             ])            

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords do not match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password: *'],
                'second_options' => ['label' => 'Repeat Password: *'],
                ])

            ->add('submit', SubmitType::class, [
                'label' => 'Create user',
            ])

            ->add('cancel', ButtonType::class, [
                'label' => 'Customers list',
                'attr' => ['class' => 'btn btn-secondary', 'id' => 'cancelButton', 'onclick' => 'window.location.href = "/admin/listUsers";'],
               ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
