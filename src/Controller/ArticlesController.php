<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticlesController extends AbstractController
{

    /** 
     * Formulaire de création d'un article
     * On utilise le service $slugger de l'interface SluggerInterface
     * 
     * @Route("/article/create", name="article_create")
     * 
     * @param Articles $article
     * @param SluggerInterface $slugger
     * 
     * @return Response
     * 
     */
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        // On autorise la création d'un article qu'aux utilisateurs qui ont un ROLE_USER (utilisateur connecté)
        $this->denyAccessUnlessGranted('ROLE_USER');

        // On prépare l'entité article
        $article = new Articles();

        // On crée le formulaire avec un Symfony
        $form = $this->createForm(ArticleType::class, $article);

        // On va faire le lien entre notre formulaire et les données de la requête
        $form->handleRequest($request);
        // A cette étape, le form de Symfony hydrate l'objet
        // C'est à dire qu'il remplit les données de l'objet avec les données du formulaire

        // On génère la date de création de l'article
        $article->setcreatedAt(new \DateTimeImmutable());

        // On va chercher l'id de l'utilisateur dans la table user
        $article->setUser($this->getUser());

        // On vérifie si le formulaire est soumis si on est en Post et aussi valide (champ non vide et syntaxe valide)
        if ($form->isSubmitted() && $form->isValid()) {

            // Génération du slug et injection dans la BDD
            $slug = $slugger->slug($article->gettitle())->lower();
            $article->setSlug($slug);

            // Insérer en BDD... Persister un objet avec Doctrine
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article); // Met de côté l'objet
            $manager->flush(); // INSERT

        // Confirmation par message "flash" de la création de l'article
        $this->addFlash(
            'success',
            'Votre article a été créé avec succès.'
        );

        // Redirection vers le nouveau article /article/le-slug-de-article
        return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);

        // ou

        // Redirection vers la page de liste des articles
        // return $this->redirectToRoute('list_article');

        }

        // Réponse: afficher la page HTML de création d'un article (create)
        return $this->render('articles/create.html.twig', [

            'form' => $form->createView(),
        ]);
    }



    /** 
     * Affichage de la liste des articles
     * 
     * @Route("/articles", name="list_article")
     * 
     * @param Article $article
     * @param ArticlesRepository $repository
     * 
     * @return Response
     */
    public function index(ArticlesRepository $repository): Response
    {
        $articles = $repository -> findAll();

        // Réponse: afficher la page HTML liste des articles (index)
        return $this -> render('articles/index.html.twig', [
            'articles' => $articles,
            ]
            );
    }



    /** 
     * Affichage d'un article avec Param Converter
     * 
     * @Route("/article/{slug}", name="article_show")
     * 
     * @param Articles $article
     * 
     * @return Response
     */
    public function show(Articles $article): Response
    {
        // Réponse: afficher la page HTML d'un article (show)
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            ]  
        );
    }



    /** 
     * Modification d'un article avec Param Converter (chargement automatique de l'article par son id)
     * 
     * @Route("/article/{id}/edit", name="article_edit")
     * 
     * @param Articles $article
     * 
     * @return Response
     */
    public function edit(Articles $article, Request $request ): Response
    {
        // On utilise la class Voter pour vérifier si l'utilisateur est connecté, s'il ne l'est pas, on redirige vers le login
        // et on vérifie qu'il est autorisé à modifier l'article, si c'est lui qu'il l'a créé, sinon on renvoie une page 403 (accès refusé)
        $this->denyAccessUnlessGranted('edit', $article);

        // Param Coverter, équivalent à un select * from article where id = $id
        $form = $this->createForm(ArticleType::class, $article);

        // on hydrate l'objet
        $form->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide, et on flush seulement car l'article est déjà dans la BDD
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

        // Message "flash" modification de l'article avec succès
        $this->addFlash(
            'success',
            'Votre article a été modifié avec succès.'
        );

        // Redirection vers la page de l'article
        return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);

        }

        // Réponse: afficher la page HTML de modification d'un article (edit)
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);

    }



    /** 
     * Suppression d'un article avec Param Converter et avec Voter
     * 
     * @Route("/article/{id}/delete", name="article_delete")
     * 
     * @param  Articles  $article
     * 
     * @return Response
     */
    public function delete(Articles $article): Response
    {
        // On utilise la class Voter pour vérifier si l'utilisateur est connecté, s'il ne l'est pas, on redirige vers le login
        // et on vérifie qu'il est autorisé à supprimer l'article, si c'est lui qu'il l'a créé
        $this->denyAccessUnlessGranted('edit', $article);
        
        // On récupère l'article
        $manager = $this->getDoctrine()->getManager();

	    // On supprime l'article
        $manager->remove($article);

        $manager->flush();

        // On redirige vers la liste des articles
        return $this->redirectToRoute('list_article');

    }

}