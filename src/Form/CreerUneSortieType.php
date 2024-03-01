<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Sortie;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;


class CreerUneSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :',
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'special-class',
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez reseigner un nom de sortie')
                ]
            ])
            ->add('dateDebut', DateTimeType::class, [
                'required' => false,
                'label' => 'Date début :',
                'constraints' => [
//                    new GreaterThan([
//                        'propertyPath' => 'dateLimiteInscription',
//                        'message' => 'la date ne peut pas être inférieur'
//                    ])
                    new NotNull(message: 'la date ne peut pas être inférieur'),
                ]
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée en minutes :',
                'required' => false,
                'constraints' => [
//                    new Type(type: Types::INTEGER, message: 'Veuillez saisir la duree en minutes'),
                    new NotNull(message: 'peut pas etre nul')
                ]
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'required' => false,
                'label' => 'Date limite de l\'inscription :',
                'constraints' => [
                    new NotNull(message: 'la date ne peut pas être supérieur'),
                ]
            ])
            ->add('nbMaxInscription', NumberType::class, [
                'required' => false,
                'label' => 'Max nombre de personnes :',
                'constraints' => [
                    new NotNull(message: 'Veuillez saisir le nombre d\'inscription possible')
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description de la sortie :',
                'required' => false,
                'attr' => [
                    'rows' => 5
                ],
                'constraints' => [
                    new NotBlank(message: 'peut pas etre vide')
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
                'label' => 'Adresse :',
                'required' => false,
                'placeholder' => 'Choisir une adresse...',
                'class' => Adresse::class,
                'choice_label' => 'nomLieu',
                'constraints' => [
                    new NotBlank(message: 'Vous devez choisir une adresse!')
                ]
            ])
//            ->add('site', EntityType::class, [
//                'label' =>'Campus',
//                'class' => Site::class,
//            'choice_label' => 'nom',
//            ])
            ->add('photo', FileType::class, [
                'required' => false,
                'label' => 'Ajouter un photo :',
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
