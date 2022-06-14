<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomComplet',TextType::class,[
            'attr' => [
                'class' => 'form-control'],
                'label' => 'Prenom et Nom'
                ])
        ->add('email',TextType::class,[
            'attr' => [
                'class' => 'form-control'],
                'label' => 'Login'
                ])
        ->add('password',RepeatedType::class,[
            'type' => PasswordType::class,
            'required' => true,
            'options' => ['attr' => ['class' => 'form-control']],
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'],    
                ])
        ->add('adresse',TextType::class,[
            'attr' => [
                'class' => 'form-control'],
                'label' => 'Adresse'
                ])
        ->add('sexe',ChoiceType::class,[
            'choices'=>[
                'Homme' =>'M',
                'Femme' =>'F'
            ],
            'attr'=>[
                'class' => 'form-control',
                'label' => 'Sexe'
            ]
        ])
        
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}