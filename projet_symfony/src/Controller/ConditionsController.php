<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    #[Route('/conditions', name: 'app_conditions')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/conditions/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
