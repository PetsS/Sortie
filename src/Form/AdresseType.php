<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdresseType extends AbstractType
{

    // utilisation de la commande : symfony console make:form    qui génére cette page avec buildForm
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLieu',TextType::class,
            ['required'=>false]
            )
            ->add('numeroRue')
            ->add('rue')
            ->add('codePostal')
            ->add('ville')

//            ->add('latitude')
//            ->add('longitude')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
