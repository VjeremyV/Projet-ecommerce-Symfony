<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Services\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request,CategoriesRepository $categoriesRepository, Panier $panier, string $ProduitDir): Response
    {
        if($request->query->get('id')){
            $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
        }

        $categories = PageController::Menu($categoriesRepository);
        $contenu = $panier->getFullPanier();
        return $this->render('front/panier/panier.html.twig', [
           'categories'=> $categories,
           'panier'=> $contenu,
           'dir' => $ProduitDir
        ]);
    }

    
    #[Route('/panier_add/{id}', name: 'app_panier_add')]
    public function panier_add(Request $request, Panier $panier, $id): Response
    {
        $panier->add($id);
        $route = $request->query->get('route');
      return $this->redirect($route);
    }



    #[Route('/panier_remove/{id}', name: 'app_panier_remove')]
    public function panier_remove(Panier $panier, $id): Response
    {
        $panier->remove($id);
      return $this->redirectToRoute('app_panier');
    }
}
