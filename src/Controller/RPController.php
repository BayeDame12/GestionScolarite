<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RO;
use App\Form\RPType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\RP;
use App\Repository\RPRepository;
class RPController extends AbstractController
{
    #[Route('/rp', name: 'app_rp')]
    public function index(RPRepository $repos): Response
    {
        return $this->render('rp/index.html.twig', [
            'controller_name' => 'RPController',
            'rps' =>$repos->findAll()

        ]);
    }
           /**
 * @Route("/addRP", name="add_rp")
 */


public function addRP(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $PasswordHascher): Response  
{
    $rp = new RP();
    $form = $this->createForm(RPType::class, $rp);
    $form->handleRequest($request);
   
    if($form->isSubmitted() && $form->isValid())
    {
        $rp=$form->getData();
        $hasher=$PasswordHascher->hashPassword($rp,$rp->getPassword());
        $rp->setRoles(["ROLE_RP"]);
        $rp->setPassword($hasher);
        $entityManager->persist($rp);
        $entityManager->flush();
        $this->redirectToRoute('app_rp');
    }

    return $this->render("rp/rp-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_Rps" => $form->createView(),
    ]);

}
}
