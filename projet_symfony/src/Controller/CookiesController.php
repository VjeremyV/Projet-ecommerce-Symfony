<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookiesController extends AbstractController
{
    /**
     * page cookies
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/cookies', name: 'app_cookies')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/cookies/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
