<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CgvController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/cgv/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
