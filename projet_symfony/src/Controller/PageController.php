<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository,CategoriesRepository $categoriesRepository, string $ProduitDir, FournisseurRepository $fournisseurRepository, string $DirFour): Response
    {   
        $fournisseurs = $fournisseurRepository->findAll();
        $produits = $produitRepository->findBy(['groupProduit'=>'PDM', 'is_active'=>true]);
        $getCategories = self::Menu($categoriesRepository);
        return $this->render('front/page/index.html.twig', [
            'categories'=> $getCategories,
            'produits'=> $produits,
            'dir' => $ProduitDir,
            'fournisseurs' => $fournisseurs,
            'fourdir'=> $DirFour
        ]);
    }

    #[Route('/categories/{idCat}', name: 'app_categories_catalogue')]
    public function categoriesCatalogue(HttpFoundationRequest $request, $idCat, CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, string $ProduitDir): Response
    {   //pour l'affichage du menu
        $getCategories = self::Menu($categoriesRepository);
        //on récupère la catégorie courante
        $categorie = $categoriesRepository->findBy(['id' => $idCat]);
        //on créée les variables de la pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $produits = $produitRepository->getPaginatorFront($offset, $categorie[0]->getId());
        $nbrePages = ceil(count($produits) / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $next = min(count($produits), $offset + ProduitRepository::PAGINATOR_PER_PAGE_FRONT);
        $pageActuelle = ceil($next / ProduitRepository::PAGINATOR_PER_PAGE_FRONT);


        $difPages = $nbrePages - $pageActuelle;
        return $this->render('front/page/categorie_catalogue.html.twig', [
            'categories'=> $getCategories,
            'categorie' =>  $categorie,
            'produits' => $produits,
            'dir' => $ProduitDir,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'offset' => ProduitRepository::PAGINATOR_PER_PAGE_FRONT,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,

        ]);
    }

    static public function Menu(CategoriesRepository $categoriesRepository){
        return $categoriesRepository->findAll();
    }

    /**
     * fonction pagination à revoir
     *
     * @param [type] $paginator_per_page
     * @param [type] $repositoryString
     * @param [type] $paginator
     * @param [type] $offset
     * @return void
     */
    static public function Pagination( $paginator_per_page, $repositoryString, $paginator, $offset){
        $nbrePages = ceil(count($paginator) / $repositoryString::$paginator_per_page);
        $next = min(count($paginator),$offset + $repositoryString::$paginator_per_page);
        $pageActuelle = ceil($next / $repositoryString::$paginator_per_page);
        $difPages = $nbrePages - $pageActuelle;

        return [$nbrePages, $next, $pageActuelle, $difPages];

    }
}
