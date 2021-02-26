<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $repo): Response
    {
        $articles = $this->getUser()->getArticles();

        return $this->render('user/profil.html.twig', [
            'controller_name' => 'UserController',
            'title' => 'profil',
            'articles'=> $articles
        ]);
    }

    //modifier la page profil

    /**
     * @Route("/inscription/edit", name="user_edit")
     */
    public function editProfile(Request $request){

        $user = $this->getUser();

        //Je vais récupérer un formulaire user sans les input mdp
        $form = $this->createForm(EditProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('user');
        }

        return $this->render('security/profilEdit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier votre profil',

        ]);

    }

        /**
     * @Route("/inscription/edit/password", name="user_edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        if ($request->isMethod('POST')) {
            # code...
        
        $em =$this->getDoctrine()->getManager();

        $user = $this->getUser();

        //on vérifie si les 2 mots de passe sont identiques
        if ($request->request->get('pass') == $request->request->get('pass1')) {

            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass1')));
            $em->flush();
            $this->addFlash('message', 'Mot de passe mis à jour avec succès');

            return $this->redirectToRoute('user');
            
        }else{
            $this->addFlash('error', 'les deux mots de passe ne sont pas identiques');
        }
    }

        return $this->render('security/editPassword.html.twig', [
            'title' => 'Modifier votre mot de passe',
        ]);
    
    }

}
