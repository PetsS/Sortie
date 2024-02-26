<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('etat',ChoiceType::class ,[
                    'required'=>false,
                    'choices'=>[
                        'EN COURS'=>'EN COURS',
                        'TERMINER'=>'TERMINER',
                        'ANNULER'=>'ANNULER',
            ],
            'row_attr' => [
        'class' => 'input-group mb-3'
                ]
    ])
            ->add('adresse', EntityType::class, [
                'label' =>'ville',
                'class' => Adresse::class,
            'choice_label' => 'ville',
            ])
            ->add('site', EntityType::class, [
                'label' =>'Campus',
                'class' => Site::class,
            'choice_label' => 'nom',
            ])
            ->add('organisateur', EntityType::class, [
                'class' => User::class,
            'choice_label' => 'nom',
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
            'choice_label' => 'nom',
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
