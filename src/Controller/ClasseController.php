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
    #[Route('/classe', name: 'app_class')]
    public function index(ClasseRepository $repos): Response
    {
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClassController',
            'classes' =>$repos->findAll()

        ]);
    }


     /**
 * @Route("/addClasse", name="classe")
 */
public function classe(Request $request, EntityManagerInterface $entityManager): Response
{
    $classe = new Classe();

            // $classe->SetRp($rp);
    $form = $this->createForm(ClasseType::class, $classe);
    $form->handleRequest($request);



    if($form->isSubmitted() && $form->isValid())
    {
        
        $entityManager->persist($classe);
        $entityManager->flush();
    }

    return $this->render("classe/addClasse-form.html.twig", [
        "form_title" => "Ajouter un classe",
        "form_classes" => $form->createView(),
    ]);
}
}
