<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AC;
use App\Form\ACType;
use App\Repository\ACRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ACController extends AbstractController
{
	public function __construct()
	{
	}
    #[Route('/listerAc', name: 'app_ac')]
    public function index(ACRepository $repos): Response
    {
        return $this->render('ac/index.html.twig', [
            'controller_name' => 'AcController',
            'acs' =>$repos->findAll()
        ]);
    }

        /**
 * @Route("/addAC", name="add_ac")
 */


public function addAC(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $ac = new AC();
    $form = $this->createForm(ACType::class, $ac);
    $form->handleRequest($request);
   
   
    
    if($form->isSubmitted() && $form->isValid())
    {
        
       $ac=$form->getData();
        $hasher=$PasswordHascher->hashPassword($ac,$ac->getPassword());
        $ac->setRoles(["ROLE_AC"]);
        $ac->setPassword($hasher);
        $entityManager->persist($ac);


        $entityManager->flush();
        $this->redirectToRoute('app_ac');
    }

    return $this->render("ac/ac-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_Acs" => $form->createView(),
    ]);

}

  
}
