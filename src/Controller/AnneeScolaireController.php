<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AnneeScolaire;
use App\Form\AnneeScolaireType;

use App\Repository\AnneeScolaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AnneeScolaireController extends AbstractController
{
    #[Route('/anneescolaire', name: 'app_annee_scolaire')]
    public function index(AnneeScolaireRepository $repos): Response
    {
        return $this->render('annee_scolaire/index.html.twig', [
            'controller_name' => 'AnneeScolaireController',
            'anneescolaires' =>$repos->findAll()

        ]);
    }

        /**
 * @Route("/addanneeScolaire", name="add_addanneeScolaire")
 */
public function addanneeScolaire(Request $request, EntityManagerInterface $entityManager): Response
{
    $anneeScolaire = new AnneeScolaire();
    $form = $this->createForm(AnneeScolaireType::class, $anneeScolaire);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->persist($anneeScolaire);
        $entityManager->flush();
    }

    return $this->render("annee_scolaire/AnneeScolaire-form.html.twig", [
        "form_title" => "Ajouter un annee_scolaire",
        "form_anneeScolaires" => $form->createView(),
    ]);
}
}
