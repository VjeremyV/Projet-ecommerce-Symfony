<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfidentController extends AbstractController
{
    /**
     * page politique de confidentialité
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/confident', name: 'app_confident')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/confident/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
