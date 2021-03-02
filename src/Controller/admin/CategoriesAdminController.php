<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategoryType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesAdminController extends AbstractController
{

    //afficher les Catégories
    /**
     * @Route("/categories", name="admin_categories")
     */
    public function categories(CategorieRepository $catsRepo): Response
    {
        return $this->render('admin/categories/categories.html.twig', [
            'title' => 'page catagories',
            'categories' => $catsRepo->findAll(),
            'controller_name' => 'CategoriesAdminController',
        ]);
    }


    /**
     * @Route("categories/ajout", name="admin_categorie_ajout")
     */
    public function ajoutCategorie(Request $request){
        $categorie = new Categorie;

        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('admin/categories/adminCategorie.html.twig', [
            'form' => $form->createView(),
            'title' => 'ajouter des catégories'

        ]);
    }



    /**
     * @Route("categories/modifier/{id}", name="admin_categorie_modifier")
     */
    public function modifierCategorie(Categorie $categorie, Request $request){

        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('admin/categories/adminCategorie.html.twig', [
            'form' => $form->createView(),
            'title' => 'ajouter des catégories'

        ]);
    }
    /**
     * @Route ("categories/modifier/{id}/delete", name="delete_article_admin")
     */
    public function delete(Categorie $categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('message', 'Supprimé avec succès');
        return $this->redirectToRoute("admin_categories");
    }


}
