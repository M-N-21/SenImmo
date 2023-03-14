<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Maison;
use App\Repository\MaisonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratVenteMaisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'disabled' => true,
            ])
            // ->add('dateContrat')
            ->add('client')
            ->add('montant')
            ->add('user')
            ->add('maison');
                // ->add('maison', EntityType::class, [
                // 'class' => Maison::class,
                // "query_builder" => function (MaisonRepository $repository) {
                //     return $repository->disponible();
                // },
            // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}