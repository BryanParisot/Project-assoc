<?php

namespace App\Controller\admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    
    //afficher les Catégories
    /**
     * @Route("/annonces", name="admin_annonces")
     */
    public function categories(ArticleRepository $repoArticle): Response
    {
        return $this->render('admin/annonces/annonces.html.twig', [
            'title' => 'page annonces',
            'annonces' => $repoArticle->findAll(),
            'controller_name' => 'AnnoncesController',
        ]);
    }




    /**
     * @Route("annonces/modifier/{id}", name="admin_annonce_modifier")
     */
    public function modifierAnnonce(Article $article, Request $request){

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_annonces');
        }
        return $this->render('admin/annonces/adminCategorie.html.twig', [
            'form' => $form->createView(),
            'title' => 'ajouter des catégories'

        ]);
    }

}
