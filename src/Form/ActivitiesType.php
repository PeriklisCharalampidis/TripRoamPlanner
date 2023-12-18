<?php

namespace App\Form;

use App\Entity\Activities;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('location', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('date', null, [
                'attr' => ['class' => 'form-control '],
                "widget" => "single_text"
            ]);

        // ->add('fk_trips', EntityType::class, [
        //     'class' => Trip::class,
        //     'choice_label' => 'id',
        //     'multiple' => true,
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activities::class,
        ]);
    }
}
