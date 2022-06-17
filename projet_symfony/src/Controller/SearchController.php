<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(CategoriesRepository $categoriesRepository, Request $request, ProduitRepository $produitRepository, string $ProduitDir): Response
    {
        $categories = PageController::Menu($categoriesRepository);
        $offset = max(0, $request->query->getInt('offset', 0));
        $produits = $produitRepository->getPaginatorSearch($offset, $request->query->get('search'));
        $nbrePages = ceil(count($produits) / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $next = min(count($produits), $offset + ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $pageActuelle = ceil($next / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('/front/page/search/index.html.twig', [
            'categories'=> $categories,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'offset' => ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
            'dir' => $ProduitDir,
            'produits' => $produits,

        ]);
    }
}
