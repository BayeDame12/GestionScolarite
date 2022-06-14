<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Entity\Inscription;
use App\Entity\AnneeScolaire;
use App\Repository\EtudiantRepository;
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
    public function index(EtudiantRepository $repos): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
            'etudiants' =>$repos->findAll()

        ]);
    }


        /**
 * @Route("/addEtudiant", name="add_etudiant")
 */


public function addEtudiant(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $etudiant = new Etudiant();
    $anneescolaire = new AnneeScolaire();

    $form = $this->createForm(EtudiantType::class, $etudiant);
    $form->handleRequest($request);
 
    if($form->isSubmitted() && $form->isValid())
    {
        dd('ok');
        $etudiant=$form->getData();     
        $pass=$etudiant->getPassword();
        $hasher=$PasswordHascher->hashPassword($etudiant,$pass);
        $etudiant->setRoles(["ROLE_ETUDIANT"]);
        $etudiant->setMatricule("MAT_".date('dmYhis'));
        $etudiant->setPassword($hasher);
        $entityManager->persist($etudiant);
        $ins=new Inscription();
        $ins->setAC($this->getUser());
        $ins->setAnneescolaire($anneescolaire);
        $ins->setEtudiant($etudiant);
        $classe=$form->get('classe')->getData();
        $ins->setClasse($classe);
        $entityManager->persist($ins);
        $entityManager->flush();
        $this->redirectToRoute('lister_etudiant');
    }


    return $this->render("etudiant/etudiant-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_etudiants" => $form->createView(),
    ]);

}
}
