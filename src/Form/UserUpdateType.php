<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'E-mail adresse :',
                'attr' => ['autocomplete' => 'email']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Votre nom :',
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom :',
                'required' => false
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :',
                'required' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone :',
                'required' => false
            ])
            ->add('isActif', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false
            ])
            ->add('photo', FileType::class, [
                'required' => false,
                'label' => 'Pohot de votre profil :',
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
            ])
            ->add('site', EntityType::class, [
                'label' => 'Campus :',
                'class' => Site::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un campus',
                'required' => false,
                'query_builder' => function(SiteRepository $siteRepository) {
                    return $siteRepository
                        ->createQueryBuilder('site')
                        ->addOrderBy('site.nom');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
//                    'class' => 'btn btn-primary mr-2'
                ]
            ])
            ->add('return', ButtonType::class, [
                'label' => 'Retour',
                'attr' => [
//                    'class' => 'btn btn-secondary',
                    'onclick' => 'history.back()'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
