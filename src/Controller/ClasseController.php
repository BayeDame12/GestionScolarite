<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
class ClasseController extends AbstractController
{
    #[Route('/listerClasse', name: 'app_classe')]
    public function index(ClasseRepository $repos): Response
    {
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClasseController',
            'classes' =>$repos->findAll()
        ]);
    }
        /**
 * @Route("/addClasse", name="add_classe")
 */
public function addClasse(Request $request, EntityManagerInterface $entityManager): Response  
{
    $classe = new Classe();
    $form = $this->createForm(ClasseType::class, $classe);
    $form->handleRequest($request);
   
     
    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->persist($classe);
        $entityManager->flush();
        $this->redirectToRoute('/classe');
    }

    return $this->render("classe/addClasse-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_Classes" => $form->createView(),
    ]);

}

}
