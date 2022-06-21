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
  /**
   * page panier
   *
   * @param Request $request
   * @param CategoriesRepository $categoriesRepository
   * @param Panier $panier
   * @param string $ProduitDir
   * @param ProduitRepository $produitRepository
   * @return Response
   */
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request,CategoriesRepository $categoriesRepository, Panier $panier, string $ProduitDir, ProduitRepository $produitRepository): Response
    {
      //si on a une query string 'id'
        if($request->query->get('id')){
          //on récupère le produit correspondant
          $produit=$produitRepository->find($request->query->get('id'));
          //si le stock est supérieur ou égal à la quantité, voir methode dans le service panier
          if($produit->getStock() >= $request->query->get('quantite')){
            //on met à jour la panier,
            $panier->modifPanier($request->query->get('id'), $request->query->get('quantite'));
          } else {
            //on envoie un message flash d'erreur avec le flag 'error'
            $this->addFlash('error', 'Il ne reste plus que '.$produit->getStock().' '.$produit.' en stock');

          }
        }
        //on calcule le total du panier, voir methode dans le service panier
        $total = $panier->getTotal();

        //on récupère l'utilisateur courant
        $user = $this->getUser();
         //on récupère les catégories pour les afficher dans le menu

        $categories = PageController::Menu($categoriesRepository);
        //on récupère le contenu du panier, voir methode dans le service panier
        $contenu = $panier->getFullPanier();

        return $this->render('front/panier/panier.html.twig', [
           'categories'=> $categories,
           'panier'=> $contenu,
           'dir' => $ProduitDir,
           'total' => $total,
           'user' => $user
        ]);
    }

    
    /**
     * url ajout produit panier
     *
     * @param Request $request
     * @param Panier $panier
     * @param [type] $id
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    #[Route('/panier_add/{id}', name: 'app_panier_add')]
    public function panier_add(Request $request, Panier $panier, $id, ProduitRepository $produitRepository): Response
    {
      //on récupère le produit 
      $produit=$produitRepository->find($id);
      //si le stock du produit est supérieur à 0
      if($produit->getStock() > 0){
        //on ajoute un produit au panier, voir methode dans le service panier
        $panier->add($id);
      } else {
        //on envoie un message flash d'erreur avec le flag 'error'
        $this->addFlash('error', 'Attention cet article est en rupture de stock');
      }
      //on récupère la route
      $route = $request->query->get('route');
      //on redirige vers la route indiquée
      return $this->redirect($route);
    }



    /**
     * url de suppression d'un produit
     *
     * @param Panier $panier
     * @param [type] $id
     * @return Response
     */
    #[Route('/panier_remove/{id}', name: 'app_panier_remove')]
    public function panier_remove(Panier $panier, $id): Response
    {
      //on supprime le produit, voir methode dans service Panier
        $panier->remove($id);
        //on redirige sur le panier
      return $this->redirectToRoute('app_panier');
    }
}
