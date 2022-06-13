<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }



        /**
 * @Route("/addInscription", name="add_inscription")
 */


public function addInscription(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $inscription = new Inscription();
    $etudiant = new Etudiant();

    $formIns = $this->createForm(InscriptionType::class, $inscription);
    $form = $this->createForm(EtudiantType::class, $etudiant);

    $formIns->handleRequest($request);
    $form->handleRequest($request);

    if($formIns->isSubmitted() && $formIns->isValid())

    {
      
       $inscription->setEtudiant($etudiant);
       $entityManager->persist($etudiant);
       
       


        $entityManager->flush();
        $this->redirectToRoute('app_ac');



    }
    return $this->render("inscription/inscription-form.html.twig", [
        "form_title" => "Ajouter un inscription",
        "form_inscriptions" => $formIns->createView(),
    ]);
}
}
