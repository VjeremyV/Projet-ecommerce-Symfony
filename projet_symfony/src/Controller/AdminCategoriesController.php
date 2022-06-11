<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function index(): Response
    {
        return $this->render('admin_categories/index.html.twig', [
            'controller_name' => 'AdminCategoriesController',
        ]);
    }
}
