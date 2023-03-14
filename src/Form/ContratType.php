<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Maison;
use App\Repository\MaisonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            // ->add('dateContrat')
            ->add('montant')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('user')
            ->add('maison')
                // ->add('maison', EntityType::class, [
                // 'class' => Maison::class,
                // "query_builder" => function (MaisonRepository $repository) {
                //     return $repository->disponible();
                // },
            // ])
            ->add('parcelle')
            ->add('appartement')
            ->add('client');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}