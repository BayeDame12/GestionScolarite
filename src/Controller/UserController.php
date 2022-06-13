<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
* @Route("/user")
*/
class UserController extends AbstractController
{
        /**
        * @Route("/", name="user_index", methods={"GET"})
        */
        public function index(UserRepository $userRepository, Session $session): Response
        {
                //besoin de droits admin
                $user = $this->getUser();
                if(!$user)
                {
                        $session->set("message", "Merci de vous connecter");
                        return $this->redirectToRoute('app_login');
                }

                else if(in_array('ROLE_ADMIN', $user->getRoles())){
                        return $this->render('user/index.html.twig', [
                                'users' => $userRepository->findAll(),
                        ]);
                }

                return $this->redirectToRoute('home');
        }

        /**
         * @Route("/new", name="user_new", methods={"GET","POST"})
         */
        public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, Session $session): Response
        {

                //test de sécurité, un user connecté ne peut pas s'inscrire
                $user = $this->getUser();
                if($user)
                {
                        $session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
                        return $this->redirectToRoute('membre');
                }

                $user = new User();
                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
                        /* uniquement pour créer un admin
                        $role = ['ROLE_ADMIN'];
                        $user->setRoles($role); */
                        $entityManager->persist($user);
                        $entityManager->flush();

                        return $this->redirectToRoute('user_index');
                }

                return $this->render('user/new.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                ]);
        }

        /**
         * @Route("/{id}", name="user_show", methods={"GET"})
         */
        public function show(User $user): Response
        {
                //accès géré dans le security.yaml
                return $this->render('user/show.html.twig', [
                'user' => $user,
                ]);
        }

        /**
         * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
         */
        public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, Session $session, $id): Response
        {
                $user = $this->getUser();
                if($user->getId() != $id )
                {
                        // un user ne peut pas en modifier un autre
                        $session->set("message", "Vous ne pouvez pas modifier cet user");
                        return $this->redirectToRoute('membre');
                }
                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
                        $this->getDoctrine()->getManager()->flush();

                        return $this->redirectToRoute('user_index');
                }

                return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                ]);
        }

        /**
         * @Route("/{id}", name="user_delete", methods={"DELETE"})
         */
        public function delete(Request $request, User $user, Session $session, $id): Response
        {
                $user = $this->getUser();
                if($user->getId() != $id )
                {
                        // un user ne peut pas en supprimer un autre
                        $session->set("message", "Vous ne pouvez pas supprimer cet user");
                        return $this->redirectToRoute('membre');
                }

                if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')))
                {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($user);
                        $entityManager->flush();
                        // permet de fermer la session user et d'éviter que l'EntityProvider ne trouve pas la session
                        $session = new Session();
                        $session->invalidate();
                }

                return $this->redirectToRoute('home');
        }
}