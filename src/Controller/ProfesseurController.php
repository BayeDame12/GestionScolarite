<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfesseurController extends AbstractController
{
    #[Route('/professeur', name: 'app_professeur')]
    public function index(ProfesseurRepository $repos): Response
    {
        return $this->render('professeur/index.html.twig', [
            'controller_name' => 'ProfesseurController',
            'professeurs' =>$repos->findAll()

        ]);
    }

        /**
 * @Route("/addProffesseur", name="add_professeur")
 */


public function addProffesseur(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $professeur = new Professeur();
    $form = $this->createForm(ProfesseurType::class, $professeur);
    $form->handleRequest($request);
   
    if($form->isSubmitted() && $form->isValid())
    {
        
       $professeur=$form->getData();
        $entityManager->persist($professeur);
        $entityManager->flush();
        $this->redirectToRoute('app_professeur');
    }

    return $this->render("professeur/professeur-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_Professeurs" => $form->createView(),
    ]);

}



}
