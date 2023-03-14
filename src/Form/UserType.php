<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                "multiple" => true,
                "expanded" => true,
                "choices" => [
                    "User" => "ROLE_USER",
                    "Admin" => "ROLE_ADMIN",
                    "Commercial" => "ROLE_COM",
                ]
            ])
            ->add('password', PasswordType::class)
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telephone');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}