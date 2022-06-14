<?php

namespace App\Form;

use App\Entity\AC;
use App\Entity\AnneeScolaire;
use App\Entity\Classe;
use App\Entity\Inscription;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('date_inscription')
        ->add('etudiant',EtudiantFormType::class)
            ->add('annee_scolaire',EntityType::class,[
                'class' => AnneeScolaire::class,
                'choice_label' => 'libelle',
                'attr' => [
                    'class' => 'form-controle  mt-3 mb-2'],
                    'label' => 'Annee Scolaire'
            ])
            ->add('Classe',EntityType::class,[
                'class' => Classe::class,
                'choice_label' => 'libelle',
                'attr' => [
                    'class' => 'form-control mb-2'],
                    'label' => 'Classe'
            ])
            ->add('AC',EntityType::class,[
                'class' => AC::class,
                'choice_label' => 'nomComplet',
                'attr' => [
                    'class' => 'form-control'],
                    'label' => 'Attaché'
                ])

            
            ->add('submit',SubmitType::class,[
                'attr' =>[
                    'class' => 'btn-primary mt-5'
                    ]
                ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}