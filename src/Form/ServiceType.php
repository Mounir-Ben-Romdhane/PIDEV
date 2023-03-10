<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraint\File;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titreS', TextType::class, [
            "attr" => [
                "placeholder" => "titreS",
                'empty_data'=>''
            ] 
        ])
        ->add('descriptionS', TextType::class, [
            "attr" => [
                "placeholder" => "descriptionS",
                'empty_data'=>''
            ] 
        ])
        ->add('typeS', ChoiceType::class, [
            'choices' => [
                "" => "",
                "Méditation"=>"Méditation",
                "Médecine douce"  => "Médecine douce",
                "Coaching"  => "Coaching",
                "Consultation psychologique"  => "Consultation psychologique",
            ],
            'empty_data'=>'',
            'data_class'=> null
        ])
        
        ->add('image', FileType::class, [
                    'label' => 'Image ( JPEG, PNG ou GIF)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new ConstraintsFile([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier JPEG, PNG ou GIF valide',
                        ])
                    ]
                ])
            
        
      
        
        ->add('dateS', DateTimeType::class)
        

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
