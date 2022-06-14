<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProduitRepository $produitRepository,CategoriesRepository $categoriesRepository, string $ProduitDir, FournisseurRepository $fournisseurRepository, string $DirFour): Response
    {   
        $fournisseurs = $fournisseurRepository->findAll();
        $produits = $produitRepository->findBy(['groupProduit'=>'PDM']);
        $getcaract = $categoriesRepository->getListCategories();
        return $this->render('front/page/index.html.twig', [
            'getcaract'=> $getcaract,
            'produits'=> $produits,
            'dir' => $ProduitDir,
            'fournisseurs' => $fournisseurs,
            'fourdir'=> $DirFour
        ]);
    }



}
