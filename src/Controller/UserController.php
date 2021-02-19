<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        $articles = $this->getUser()->getArticles();

        return $this->render('user/profil.html.twig', [
            'controller_name' => 'UserController',
            'title' => 'profil',
            'articles'=> $articles
        ]);
    }


    /**
     * @Route("/inscription/edit", name="user_edit")
     */
    public function editProfile(Request $request){

        $user = $this->getUser();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('security/profilEdit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier votre profil',

        ]);

    }
}
