<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerUneSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbMaxInscription')
            ->add('infosSortie')
            ->add('etat')
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
            'choice_label' => 'id',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
            'choice_label' => 'id',
            ])
            ->add('organisateur', EntityType::class, [
                'class' => User::class,
            'choice_label' => 'id',
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
            'choice_label' => 'id',
            'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
