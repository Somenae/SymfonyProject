<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\Taxes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('image')
            ->add('ProductCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('ProductTaxes', EntityType::class, [
                'class' => Taxes::class,
                'choice_label' => 'id',
            ])
            ->add('ProductSales', EntityType::class, [
                'class' => Sales::class,
                'choice_label' => 'id',
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'creer un bouton'
            ]);
           
               
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
