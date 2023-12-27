<?php 
namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
        $builder
            ->add('image', Filetype::class, array('label' => 'image'.' '.'(png, jpeg)'),[
            'mapped'=> false,
            'constraints' => [
                new Product([
                    'maxSize' => '5M', // ajustez la taille maximale selon vos besoins
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image au format PNG ou JPEG.',
                ]),
            ],
        ])
            ->add('save',SubmitType::class, array('label' => 'ENVOYER'));
              
           
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

