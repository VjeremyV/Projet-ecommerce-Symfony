<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCaracteristiqueController extends AbstractController
{
    #[Route('/admin/caracteristique', name: 'app_admin_caracteristique')]
    public function index(): Response
    {
        return $this->render('admin_caracteristique/index.html.twig', [
            'controller_name' => 'AdminCaracteristiqueController',
        ]);
    }
}
