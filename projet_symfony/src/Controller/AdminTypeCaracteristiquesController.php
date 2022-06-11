<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeCaracteristiquesController extends AbstractController
{
    #[Route('/admin/type/caracteristiques', name: 'app_admin_type_caracteristiques')]
    public function index(): Response
    {
        return $this->render('admin_type_caracteristiques/index.html.twig', [
            'controller_name' => 'AdminTypeCaracteristiquesController',
        ]);
    }
}
