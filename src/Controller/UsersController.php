<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Users;
use App\Entity\Announces;
use App\Entity\Articles;
use App\Form\ImageType;
use App\Repository\AnnouncesRepository;
use App\Repository\ArticlesRepository;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ObjectManager;
use phppharser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MailerService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class UsersController extends AbstractController
{
    /**
     * @Route("Profil/edit", name="editProfil")
     */
    public function editProfil(Request $request)
    {
        $manager = $this->GetDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ],

            ])
            ->add('Firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])

            ->add('email', TextType::class, [
                'label' => 'Adresse mail',
                'attr' => [
                    'placeholder' => 'Adresse mail'
                ]
            ])
            ->add('NumberPhone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Numéro de téléphone'
                ],
                'required' => false,
            ])

            ->add('Function', TextType::class, [
                'label' => 'Statut',
                'attr' => [
                    'placeholder' => 'statut'
                ]
            ])

            ->add('currentSituation', TextType::class, [
                'label' => 'Situation actuelle',
                'attr' => [
                    'placeholder' => 'Situation actuelle'
                ],
                'required' => false,
            ])

            ->add('currentPost', TextType::class, [
                'label' => 'Poste actuel',
                'attr' => [
                'placeholder' => 'Poste actuel'
                ],
                'required' => false,
                ])

            ->add('SessionNumber', TextType::class, [
                    'label' => 'Numéro de session',
                    'attr' => [
                        'placeholder' => 'Numéro de session'
                    ],
                ])

            ->add('trainingYear', TextType::class, [
                'label' => 'Année de formation',
                'attr' => [
                    'placeholder' => 'Année de formation'
                ]
            ])


            ->add('Linkedin', TextType::class, [
                'label' => 'Profil Linkedin',
                'attr' => [
                    'placeholder' => 'Profil Linkedin'
                ],
                'required' => false,
            ])


            ->add('twitter', TextType::class, [
                'label' => 'Profil Twitter',
                'attr' => [
                    'placeholder' => 'Profil Twitter'
                ],
                'required' => false,
            ])

            ->add('github', TextType::class, [
                'label' => 'GitHub',
                'attr' => [
                    'placeholder' => ' GitHub'
                ],
                'required' => false,
            ])

            ->add('image', ImageType::class, [
                'label' => 'Avatar',
                'attr' => [
                    'placeholder' => 'Avatar',
                ],
                'required' => false,
            ])

            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('users/editProfil.html.twig', [
            'user' => $user,
            'formUser' => $form->createview(),

        ]);
    }


    /**
     * @Route("/list", name="users_list")
     */
    public function index(UsersRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('users/index.html.twig', [
            'users' => $users,

        ]);
    }


    /**
     * @Route("/show/{pseudo}", name="users_show")
     */
    public function show(Users $users)
    {

        return $this->render('users/show.html.twig', [
            'users' => $users,

        ]);
    }


    /**
     * @Route("/Profil", name="users_Profil")
     */
    public function Profil(ArticlesRepository $repoart, AnnouncesRepository $repoannoun)
    {
        $user = $this->getUser();

        $articles = $repoart->findByUser([$user], ['createdAt' => 'ASC'], 6, null);

        $announces = $repoannoun->findByUser([$user], ['createdAt' => 'ASC'], 6, null);

        return $this->render('users/Profil.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'announces' => $announces,
        ]);
    }


    /**
     * @Route("/Profil/delete", name="deleteProfil")
     */
    public function delete()
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($this->getUser());
        $manager->flush();
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('app_logout');
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UsersRepository $repository)
    {
        $users = $repository->findByActivated(0);
        return $this->render('users/Admin.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("admin/{id}/activer", name="admin_activated", methods="GET")
     */
    public function permuteActivated(UsersRepository $repository, MailerInterface $mailer, $id)
    {
        $user = $repository->findOneBy(["id" => $id]);
        $user->setActivated(true);
        $entityManager = $this->getDoctrine()->getManager();
        $email = (new TemplatedEmail())
        ->from('webforc3@gmail.com')
        ->cc('webforc3@gmail.com')
        ->to($user->getEmail())
        ->subject('Adhésion à l\'Extranet de WebForce3')
        // Renvoi vers le fichier html signactivated
        ->htmlTemplate('users/signactivated.html.twig')
        ->context([
            'user' => $user,
            ]);
$mailer->send($email);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('users_Profil');
    }
}
