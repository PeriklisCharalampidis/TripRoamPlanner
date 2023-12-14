<?php

namespace App\Form;

use App\Entity\PakingList;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PakingListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'class' => 'form-control ',
                    "placeholder" => "please input the name of the item"
                    ]
            ])
            /*->add('isPredefined', null, [
                'data' => false, // Predefined value
                'disabled' => true, // Make the field read-only
                'required' => false, // Since the field is disabled, it's not required
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('season_filter', ChoiceType::class, [
                'label' => 'Season:',
                'choices' => [
                    'any' => 'any',
                    'summer' => 'summer',
                    'winter' => 'winter'
                ],
                'disabled' => true, // Make the field read-only
                'required' => false, // Since the field is disabled, it's not required
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])*/
            /*->add('fk_trips', EntityType::class, [
                'class' => Trip::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PakingList::class,
        ]);
    }
}
