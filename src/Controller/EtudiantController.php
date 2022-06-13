<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EtudiantController extends AbstractController
{

        /**
 * @Route("/listerEtudiant", name="lister_etudiant")
 */
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }


        /**
 * @Route("/addEtudiant", name="add_etudiant")
 */


public function addEtudiant(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $etudiant = new Etudiant();
    $form = $this->createForm(EtudiantType::class, $etudiant);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $pass=$etudiant->getPassword();
        $hasher=$PasswordHascher->hashPassword($etudiant,$pass);
        $etudiant->setRoles(["ROLE_ETUDIANT"]);
        $etudiant->setPassword($hasher);
        $entityManager->persist($etudiant);
        $entityManager->flush();
        $this->redirectToRoute('app_ac');
    }


    return $this->render("etudiant/etudiant-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_etudiants" => $form->createView(),
    ]);

}
}
