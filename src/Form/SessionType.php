<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbMax', NumberType::class)
            ->add('dateDebut', DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('dateFin', TextType::class)
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label'=> 'intitule',
                // 'multiple' => true
            ])
           // ->add('stagiaires', EntityType::class, [
            //     'class' => Stagiaire::class,
            //     'choice_label' => 'email', ])

            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'email',
            // ])
//////////////////////////////////////////////////////////////////////////////////////////////////////
            // ->add('stagiaires', CollectionType::class, [
            //     'entry_type' => EntityType::class,
            //     'label'=> false,
            //     'entry_options' => ['label' => "Choisir stagiaire :", "class" => Stagiaire::class],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
////////////////////////////////////////////////////////////////////////////////////////////////////
            ->add('programmes', CollectionType::class, [
                'label'=> false,
                'entry_type' => ProgrammeType::class,
                'entry_options' => ['label' => false,
                ], 
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            
            // ->add('submit', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
