<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Inscription;
use App\Form\InscriptionFormType;
use App\Repository\ACRepository;
use App\Repository\AnneeScolaireRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }
    #[Route('/inscription_etudiant',name:'app_add_inscription_etudiant')]
    public function addInscriptionEtudiant(ManagerRegistry $registry,UserPasswordHasherInterface $hasher,Request $request,EntityManagerInterface $manager)
    {
     $acRepo=new ACRepository($registry);
     $classRepo = new ClasseRepository($registry);
     $anneeRepo = new AnneeScolaireRepository($registry);
     
     $ins= new Inscription();
     $etudiant= new Etudiant();
     $form=$this->createForm(InscriptionFormType::class,$ins);
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
         $passwword = $hasher->hashPassword($etudiant,"Passer123");
         $matricule = 'STU-'.date('Ymdhis');
         $etudiant->setNomComplet($request->get('inscription_form')['etudiant']['nomComplet']);
         $etudiant->setEmail($request->get('inscription_form')['etudiant']['email']);
         $etudiant->setAdresse($request->get('inscription_form')['etudiant']['adresse']);
         $etudiant->setSexe($request->get('inscription_form')['etudiant']['sexe']);
         $etudiant->setPassword($passwword);
         $etudiant->setMatricule($matricule);
        //  dd($ins);
         $manager->persist($etudiant);
         $ins->setEtudiant($etudiant);
         $manager->persist($ins);
         $manager->flush();
        }
     return $this->render('inscription/ajout.html.twig', [
        'form' => $form->createView()
    ]);
    }
}