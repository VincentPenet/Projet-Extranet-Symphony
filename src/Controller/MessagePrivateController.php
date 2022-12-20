<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Entity\Messages;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagePrivateController extends AbstractController
{
    /**
     * @Route("/messageprivate", name="message_private")
     */
    public function index(Request $request, UsersRepository $repository): Response
    {
            // Je prépare l'entité 
        $messages = new Messages();

        $target = $repository->findAll();
        

        // je crée un formulaire symfony 
        $form = $this->createForm(MessageType::class, $messages);

        $form->handleRequest($request);

        // si le formulaire a été soumis 

        if($form->isSubmitted()){
            
            $user =$this->getUser();
            $messages->SetUser($user);
            $messages->setCreatedAt(new \DateTime());
            $messages->setStatus(0);

            // on enregistre le champ en bdd 
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($messages);
            $em->flush(); // Insert en bdd
            
            
            $this->addFlash('success', 'Message Envoyé');

        // Redirection vers la liste des messages
        return $this->redirectToRoute('message_receveid');

        // ou

        // Redirection vers la page "voir mon profil"
        // return $this->redirectToRoute('users_Profil');

        }
        
        return $this->render('message_private/index.html.twig', [
            'MessageForm' => $form->createView(),

        ]);
    }
}
