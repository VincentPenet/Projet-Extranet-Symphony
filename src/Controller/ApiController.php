<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * Outil de localisation
     * 
     * @Route("/api", name="api")
     * 
     * @return Response
     * 
     */
    public function index(): Response
    {
        return $this->render('api/api.html.twig');
    }
}
