<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control ', "placeholder" => " email"],
            ])
            //->add('roles')
            //->add('password')
            ->add('first_name', null, [
                'attr' => ['class' => 'form-control', "placeholder" => " first name"]

            ])
            ->add('last_name', null, [
                'attr' => ['class' => 'form-control', "placeholder" => " last name"]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
