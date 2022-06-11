<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFournisseurController extends AbstractController
{
    #[Route('/admin/fournisseur', name: 'app_admin_fournisseur')]
    public function index(): Response
    {
        return $this->render('admin_fournisseur/index.html.twig', [
            'controller_name' => 'AdminFournisseurController',
        ]);
    }
}
