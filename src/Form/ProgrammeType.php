<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\ModuleFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // ->add('session', EntityType::class,[
            //     'label'=>'session',
            //     'class' => Session::class,
            //     'choice_label' =>'formation'
            // ])
            ->add('moduleFormation', EntityType::class,[
                'label'=>'Module',
                'class' => ModuleFormation::class,
                'choice_label' =>'intitule'
            ])
            ->add('nbJours', NumberType::class, [
                'label' =>'Durée (en jours)',
                'attr'  => [ 'min' =>'1', 'max' => '50']
            ])
            //  ->add('valider',SubmitType::class)
            //->add('valider', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
