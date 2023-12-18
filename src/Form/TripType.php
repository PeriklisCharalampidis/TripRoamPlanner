<?php

namespace App\Form;

use App\Entity\Activities;
use App\Entity\PakingList;
use App\Entity\Trip;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control ', "placeholder" => "give your trip a name"],
            ])
            ->add('destination', null, [
                'attr' => ['class' => 'form-control ', "placeholder" => "please add your destination"],

            ])
            ->add('date_begin', null, [
                'attr' => ['class' => 'form-control '],
                "widget" => "single_text"
            ])
            ->add('date_end', null, [
                'attr' => ['class' => 'form-control'],
                "widget" => "single_text"
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Trip photo',
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
                        'mimeTypesMessage' => 'Please upload a valid photo',
                    ])
                ],
            ]);
        //             ->add('fk_activities', EntityType::class, [
        //                 'class' => Activities::class,
        // 'choice_label' => 'id',
        // 'multiple' => true,
        //             ])
        // ->add('fk_paking_list', EntityType::class, [
        //     'class' => PakingList::class,
        //     'choice_label' => 'id',
        //     'multiple' => true,
        // ])
        //
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
