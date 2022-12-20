<?php

namespace App\Controller;


use App\Entity\Announces;
use App\Entity\AnnounceSearch;
use App\Form\AnnounceSearchType;
use App\Form\AnnouncesType;
use App\Repository\AnnouncesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AnnouncesController extends AbstractController
{

    /**
     * 
     * 
    * Formulaire de création d'une annonce
    * On utilise le service $slugger de l'interface SluggerInterface
    * 
     * @Route("/announce/create", name="create_announce")
    * 
    * @param Announces $announce
    * @param SluggerInterface $slugger
    * 
    * @return Response
    * 
    */
    public function create(Request $request, SluggerInterface $slugger): Response
    {

        //on prepare une entité

        $announce = new Announces;

        //creation de formulaire Bootstrap

        $form = $this->createForm(AnnouncesType::class, $announce);

        // le formulaire est créer dans le fichier announcesType.php qui se trouve dans le dossier Form

        //faire le lien entre le formulaire et les données de la requête

        $form->handleRequest($request);

        //on hydrate l'objet des données du formulaire

        //verifier si le formulaire est soumis et validé

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($announce->getTitle())->lower();

            $announce->setSlug($slug);


            $announce->setCreatedAt(new \DateTimeImmutable());

            //recuperer l'utilisateur connecté
            $announce->setUser($this->getUser());

            //Insertion dans la BDD...Persister un objet avec Doctrine

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($announce); //mets de côté l'objet
            $manager->flush(); //INSERT

            //on va rediriger vers la liste des annonces

            return $this->redirectToRoute('announces');
        }



        return $this->render('announces/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /** 
     * Affichage de la liste des annonces
     * 
     * @Route("/announces", name="announces")
     * 
    * @param Announces $announce
     * @param AnnouncesRepository $repository
     * 
     * @return Response
     */
    public function index(AnnouncesRepository $announcesRepository): Response
    {
        //on recupère les annonces dans la BDD

        //$announcesRepository = $this->getDoctrine()
        //  ->getRepository(users::class)->findAll();
        $repository = $this->getDoctrine()->getRepository(Announces::class);

        //recuperer toutes les annonces

        $announces = $repository->findAll();

        //Le produit avec l'id

        $repository->find(1);

        return $this->render('announces/index.html.twig', [
            'announces' => $announces,

        ]);
    }


    /** 
     * Affichage d'une annonce avec Param Converter
     * 
     * @Route("/announce/{slug}", name="announce_show")
     * 
    * @param Announces $announce
     * 
     * @return Response
     */
    public function show(Announces $announce)
    {

        return $this->render('announces/show.html.twig', [
            'announce' => $announce,
        ]);
    }


    /** 
     * Modification d'une annonce avec Param Converter (chargement automatique de l'annoce par son id)
     * 
     * @Route("/announce/{id}/edit", name="announce_edit")
     * 
    * @param Announces $announce
     * 
     * @return Response
     */
    public function edit(Request $request, Announces $announce): Response
    {
        //l'utilisateur doit être connecté s'il ne l'ai pas on redirige

        $this->denyAccessUnlessGranted('edit', $announce);

        $form = $this->createForm(AnnouncesType::class, $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announces', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('announces/edit.html.twig', [
            'announce' => $announce,
            'form' => $form,
        ]);
    }

    /** 
     * Suppression d'un article avec Param Converter et avec Voter
     * 
     * @Route("/{id}/delete", name="announce_delete")
     * 
    * @param Announces $announce
     * 
     * @return Response
     */
    public function delete(Request $request, Announces $announce): Response
    {
        $this->denyAccessUnlessGranted('delete', $announce);

        //if ($this->isCsrfTokenValid('delete' . $announce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announce);
            $entityManager->flush();
        //}

        return $this->redirectToRoute('announces', [], Response::HTTP_SEE_OTHER);
    }
}

