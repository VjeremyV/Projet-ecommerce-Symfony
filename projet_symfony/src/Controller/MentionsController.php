<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    #[Route('/mentions', name: 'app_mentions')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $getCategories = PageController::Menu($categoriesRepository);

        return $this->render('front/page/mentions/index.html.twig', [
            'categories' => $getCategories,
        ]);
    }
}
