<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProduitController extends AbstractController
{
    #[Route('/admin/produit', name: 'app_admin_produit')]
    public function index(): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
        ]);
    }
}
