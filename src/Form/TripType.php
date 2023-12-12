<?php

namespace App\Form;

use App\Entity\Activities;
use App\Entity\PakingList;
use App\Entity\Trip;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('destination')
            ->add('date_begin')
            ->add('date_end')
            ->add('fk_activities', EntityType::class, [
                'class' => Activities::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('fk_paking_list', EntityType::class, [
                'class' => PakingList::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('fk_user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
