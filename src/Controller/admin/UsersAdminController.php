<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersAdminController extends AbstractController
{
    
    /**
     * @Route("/admin/users", name="admin_users_admin")
     */
    public function categories(UserRepository $repoArticle): Response
    {
        return $this->render('admin/users/users.html.twig', [
            'title' => 'page utilisateurs',
            'users' => $repoArticle->findAll(),
            'controller_name' => 'UsersAdminController',
        ]);
    }

    /**
     * @Route("/admin/users/modifier/{id}", name="admin_users_modifier")
     */
    public function modifierAnnonce(User $user, Request $request){

        $form = $this->createForm(EditProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_users_admin');
        }
        return $this->render('admin/users/modifierUser.html.twig', [
            'form' => $form->createView(),
            'title' => 'modification d\'utilisateurs'

        ]);
    }

    /**
     * @Route("/inscription/edit/password/{id}", name="user_edit_password_admin")
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

            return $this->redirectToRoute('admin_users_admin');
            
        }else{
            $this->addFlash('error', 'les deux mots de passe ne sont pas identiques');
        }
    }

        return $this->render('security/editPassword.html.twig', [
            'title' => 'Modifier votre mot de passe',
        ]);
    
    }

}
