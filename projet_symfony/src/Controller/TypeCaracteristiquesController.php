<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeCaracteristiquesController extends AbstractController
{
    #[Route('/type/caracteristiques', name: 'app_type_caracteristiques')]
    public function index(): Response
    {
        return $this->render('type_caracteristiques/index.html.twig', [
            'controller_name' => 'TypeCaracteristiquesController',
        ]);
    }
}
