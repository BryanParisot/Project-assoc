<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription" , name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $hash = $encoder->encodePassword($user, $user->getPassword());
            
            $user->setPassword($hash);
            
            // return $this->redirectToRoute("security_login");
            
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('message', 'Profil crée avec succès');

            return $this->redirectToRoute('security_login');

        }
        

        return $this->render('security/registration.html.twig', [
            'title' => 's\'inscrire',
            'editMode' => $user->getId() !== null,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig',[
            'title' => 'Connexion'
        ]);
        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        
    }

}
