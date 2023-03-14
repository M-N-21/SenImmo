<?php

namespace App\Form;

use App\Entity\Immeuble;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmeubleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('localite')
            ->add('nbreEtage')
            ->add('dateConstruction')
            ->add('nomImmeuble')
            ->add('description')
            ->add('disponibilite')
            ->add('offre')
            ->add('images', FileType::class, [
                'label' => 'Choisissez des images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Immeuble::class,
        ]);
    }
}