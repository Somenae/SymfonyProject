<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\Taxes;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\Mapping as ORM;



class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['label' => 'Groupe'])
        ->add('description', null, ['label' => 'Album'])
        ->add('price', null, ['label' => 'Prix'])
        ->add('ProductCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['class' => 'form-control', 'style' => 'width: 50%;', ],
                'choice_attr' => function($category, $id) {
                    return ['class' => 'option-bootstrap-class'];
                },
                'label' => 'Catégories', 
            ])
            
        ->add('ProductTaxes', EntityType::class, [
                'class' => Taxes::class,
                'choice_label' => 'amount',
                'label' => 'TVA (%)', 
                'placeholder' => 'Select an option',
            ])
        ->add('ProductSales', EntityType::class, [
                'class' => Sales::class,
                'choice_label' => 'amount_percentage',
                'label' => 'Promo (%)',
                'placeholder' => 'Select an option',
                'required' => false,

        ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}


