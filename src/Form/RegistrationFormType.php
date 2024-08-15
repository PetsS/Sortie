<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping\Entity;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\NotNull;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => ['autocomplete' => 'email'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre email',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Votre nom :',
                'required' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez remplir ce champ'),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom :',
                'required' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez remplir ce champ'),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :',
                'required' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez remplir ce champ'),
                ],
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
                },
                'constraints' => [
                new NotBlank(message: "Veuillez choisir un campus."),
                ],
            ])
//            ->add('agreeTerms', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new IsTrue([
//                        'message' => 'You should agree to our terms.',
//                    ]),
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
//            'validation_groups' => false, // Disable default validation groups
        ]);
    }
}
