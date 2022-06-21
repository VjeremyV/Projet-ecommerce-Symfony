<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CgvController extends AbstractController
{
    /**
     * page cgv
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/cgv', name: 'app_cgv')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/cgv/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
