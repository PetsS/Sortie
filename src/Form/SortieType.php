<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Repository\SortieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', SearchType::class, [
                'label' => 'Rechercher:',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mr-sm-2',
                    'placeholder' => 'Le nom de la sortie contient...'
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'required' => false,
                'label' => 'Entre :',
                'attr' => [
                    'class' => 'form-control mr-sm-2',
                ]
            ])
            ->add('dateFin', DateType::class, [
                'required' => false,
                'label' => 'et :',
                'attr' => [
                    'class' => 'form-control mr-sm-2',
                ]
            ])
            ->add('checkOrganisateur', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie.s où je suis organisateur',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check mr-sm-2 ',
                ],
                'label_attr' => [
                    'style' => 'margin-left: 10px;',
                ]

            ])

            ->add('checkParticipant', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie.s où je participe',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check mr-sm-2',
                ],
                'label_attr' => [
                    'style' => 'margin-left: 10px;',
                ]
            ])
            ->add('checkNonParticipant', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie.s où je ne participe pas',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check mr-sm-2',
                ],
                'label_attr' => [
                    'style' => 'margin-left: 10px;',
                ]
            ])

            ->add('datePasse', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie.s terminée.s et en cours',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check mr-sm-2',
                ],
                'label_attr' => [
                    'style' => 'margin-left: 10px;',
                ]
            ])

//            ->add('duree')
//            ->add('dateLimiteInscription')
//            ->add('nbMaxInscription')
//            ->add('infosSortie')
//            ->add('etat', ChoiceType::class, [
//                'label' => 'Etat',
//                'required' => false,
//                'choices' => [
//                    'en cours' => '1',
//                    'terminé' => '2',
//                    'fermé' => '3',
//                ],
//            ])
//            ->add('adresse', EntityType::class, [
//                'class' => Adresse::class,
//                'choice_label' => 'id',
//            ])
//            ->add('site', EntityType::class, [
//                'class' => Site::class,
//                'choice_label' => 'id',
//            ])
//            ->add('organisateur', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'nom',
//            ])
//            ->add('participants', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])

            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'required' => false,
                'label' => 'Campus',
                'placeholder' => 'Tous...',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary mr-2'
                ]
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


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
