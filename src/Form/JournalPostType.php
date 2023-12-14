<?php

namespace App\Form;

use App\Entity\JournalPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class JournalPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('text')
            ->add('image', FileType::class, [
                'attr' => ['accept'=> 'image/*'],
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1400k',
                        'mimeTypes' => [
                            'iamge/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])

            // ->add('fk_trip', EntityType::class, [
            //     'class' => Trip::class,
            //     'choice_label' => 'id',
            // ])
           ;
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalPost::class,
            'fk_trip_default' => null, // Define the custom option here
        ]);
    }
}
