<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'app_admin_categorie')]
    public function index(): Response
    {
        return $this->render('admin_categorie/index.html.twig', [
            'controller_name' => 'AdminCategorieController',
        ]);
    }
}
