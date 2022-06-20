<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitRepository;
use App\Services\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request,CategoriesRepository $categoriesRepository, Panier $panier, string $ProduitDir, ProduitRepository $produitRepository): Response
    {
        if($request->query->get('id')){
          $produit=$produitRepository->find($request->query->get('id'));
          if($produit->getStock() >= $request->query->get('quantite')){
            $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
          } else {
            $this->addFlash('error', 'Il ne reste plus que '.$produit->getStock().' '.$produit.' en stock');

          }
        }
        $total = $panier->getTotal();
        $user = $this->getUser();
        $categories = PageController::Menu($categoriesRepository);
        $contenu = $panier->getFullPanier();
        return $this->render('front/panier/panier.html.twig', [
           'categories'=> $categories,
           'panier'=> $contenu,
           'dir' => $ProduitDir,
           'total' => $total,
           'user' => $user
        ]);
    }

    
    #[Route('/panier_add/{id}', name: 'app_panier_add')]
    public function panier_add(Request $request, Panier $panier, $id, ProduitRepository $produitRepository): Response
    {
      $produit=$produitRepository->find($id);
      if($produit->getStock() > 0){
        $panier->add($id);
      } else {
        $this->addFlash('error', 'Attention cet article est en rupture de stock');
      }
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
