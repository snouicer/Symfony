<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sexe', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('ville', TextType::class)
            ->add('cp', TextType::class)
            ->add('email', TextType::class)
            ->add('tel', TextType::class)
            ->add('sessions', EntityType::class, [
                    'class' => Session::class,
                    'choice_label'=> 'formation',
                    'multiple' => true
            ])
            ->add('valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
