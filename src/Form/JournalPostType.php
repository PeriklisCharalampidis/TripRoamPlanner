<?php

namespace App\Form;

use App\Entity\JournalPost;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class JournalPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('text')
            ->add('image')
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
