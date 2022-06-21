<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    /**
     * page mentions légales
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/mentions', name: 'app_mentions')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/mentions/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
