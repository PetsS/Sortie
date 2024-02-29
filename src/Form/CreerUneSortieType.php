<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class CreerUneSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => 'Nom de la sortie',
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'special-class',
                ],

                ])

            ->add('dateDebut' , DateTimeType::class, [
                'required' => false,

            ])
            ->add('duree', NumberType::class,[
                'label' => 'Duree',
                'required' => false,
                ])
            ->add('dateLimiteInscription' , DateTimeType::class, [
                'required' => false,

            ])

            ->add('nbMaxInscription')

            ->add('infosSortie', TextareaType::class, [
                'label' =>'Description de la sortie',
                'required' =>false,
                'attr' => [
                    'rows' => 5
                ]
            ])
//            ->add('etat',ChoiceType::class ,[
//                'required'=>false,
//                'choices'=> [
//                    'EN COURS'=>'EN COURS',
//                    'TERMINER'=>'TERMINER',
//                    'ANNULER'=>'ANNULER',
//                    ],
//                'row_attr' => [
//                    'class' => 'input-group mb-3'
//                ]
//            ])
            ->add('adresse', EntityType::class, [
                'label' =>'Nom lieu',
                'class' => Adresse::class,
            'choice_label' => 'nomLieu'
            ])
            ->add('site', EntityType::class, [
                'label' =>'Campus',
                'class' => Site::class,
            'choice_label' => 'nom',
            ])
            ->add('photo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => "Ce format n'est pas bon",
                        'maxSizeMessage' => "Ce fichier est trop lourd"
                    ])
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
