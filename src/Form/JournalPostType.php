<?php

namespace App\Form;

use App\Entity\JournalPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class JournalPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control'],
            'label' => 'Date',
        ])
        ->add('text', TextareaType::class, [
            'attr' => ['class' => 'form-control', 'rows' => 5],
            'label' => 'Text',
        ])
        ->add('image', FileType::class, [
            'attr' => ['class' => 'form-control', 'accept' => 'image/*'],
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1400k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpg',
                        'image/jpeg',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image',
                ])
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalPost::class,
            'fk_trip_default' => null,
        ]);
    }
}
