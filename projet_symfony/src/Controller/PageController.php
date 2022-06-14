<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $getcaract = $categoriesRepository->getListCategories();
        return $this->render('front/page/index.html.twig', [
            'getcaract'=> $getcaract
        ]);
    }



}
