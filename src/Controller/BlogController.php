<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Texter;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    //Permet de créer le rendu, et de lui donner le chemin en url (Route)

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'title' => 'Liste des animaux',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Accueil'
        ]);
    }

    //---------------------Créer le formulaire de création d'article---------------


    /**
     * @Route("/blog/new", name="blog_annonce")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function createAnnonce(Article $article = null, Request $request, EntityManagerInterface $manager)
    {

        // si je n'ai pas d'article(post)
        if (!$article) {
            $article = new Article(); // article vide

        }


        // si on utilise pas la cli on peut le faire a la main sinon 
        // $form = $this->createFormBuilder($article)
        //                 ->add('prenom')
        //                 ->add('race')
        //                 ->add('sexe')
        //                 ->add('Age')
        //                 ->add('descriptif' ,TextareaType::class)
        //                 ->add('grand_descriptif')
        //                 ->add('entente')

        //                 ->getForm();


        //direction -> form articletype
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);
                $article->addImage($img);
            }

            //si article n'existe pas (date à jour)
            if (!$article->getId()) {
                $article->setcreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/createAnnonce.html.twig', [
            'title' => 'Créer une annonce',
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    //---------------------on va récupérer l'id de l'article, dans article.---------------


    /**
     * @Route ("/blog/{id}", name="blog_show", requirements={"id":"\d+"})
     */
    public function show(Article $article)
    {

        return $this->render('blog/show.html.twig', [
            'title' => 'annonce',
            'article' => $article
        ]);
    }
}
