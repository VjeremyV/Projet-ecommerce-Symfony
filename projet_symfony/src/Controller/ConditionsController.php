<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    /**
     * page Conditions d'utilisaton
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/conditions', name: 'app_conditions')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/conditions/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
