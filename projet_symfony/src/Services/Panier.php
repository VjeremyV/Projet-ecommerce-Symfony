<?php

namespace App\Services;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Panier{

    protected $session;
    protected $ProduitRepository;

    public function __construct(RequestStack $session, ProduitRepository $produitRepository){
        $this->session = $session;
        $this->ProduitRepository = $produitRepository;
    }
    /**
     * ajout d'un élément au panier
     *
     * @param integer $id du produit
     * @param integer $quantite du produit
     * @return void
     */
    public function add(int $id, int $quantite = null){
        $panier = $this->session->getSession()->get('panier', []);//si un panier existe on le récupère ou alors on initialise un panier avec un tableau vide

        if(!empty($panier[$id]) && $quantite){//si l'entrée id n'est pas vide dans le panier
            $panier[$id] += $quantite;
        } elseif(!empty($panier[$id])) {
            $panier[$id]++;//on l'incrémente
        } else {
            $panier[$id]=1;//sinon on l'établie à 1
        }
        $this->session->getSession()->set('panier', $panier);//on passe la nouvelle valeur de notre panier à la session
    }
    /**
     * supprimer un élément du panier
     *
     * @param integer $id du produit
     * @return void
     */
    public function remove( $id){
        $panier = $this->session->getSession()->get('panier', []);//si un panier existe on le récupère ou alors on initialise un panier avec un tableau vide
        if(!empty($panier[$id])){//si l'entrée id n'est pas vide dans le panier
            unset($panier[$id]);//on supprime cette entrée
        }
        $this->session->getSession()->set('panier', $panier);//on passe la nouvelle valeur de notre panier à la session
    }
    /**
     * récuperer le panier documenté
     * @return array
     */
    public function getFullPanier():array{
        $panier = $this->session->getSession()->get('panier', []);//si un panier existe on le récupère ou alors on initialise un panier avec un tableau vide
        $fullPanier = [];//on créée un tableau vide qui représentera notre panier documenté

        foreach($panier as $id => $quantite){//on boucle sur notre panier et on incrémente notre panier documenté avec les variables correspondantes
            $fullPanier[]= [
                'produit' => $this->ProduitRepository->find($id),
                'quantite' =>$quantite
            ];
        }
        return $fullPanier;//on retourne le panier documenté
    }

/**
 * modifier un panier
 *
 * @param [type] $id
 * @param [type] $quantite
 * @return void
 */
    public function modifPanier($id, $quantite){
        //on récupère le panier
        $panier = $this->session->getSession()->get('panier', []);
        //on modifie la quantité
        $panier[$id]= $quantite;
        // on met à jour le panier
        $this->session->getSession()->set('panier', $panier);
    }

    /**
     * obtenir le total d'un panier
     * @return float
     */
    public function getTotal():float{
        $total = 0;//on créée une variable total que l'on définit à 0
        $fullPanier = $this->getFullPanier();//on récupère notre panier documenté

        foreach($fullPanier as $ligne){//pour chaque ligne de produit
            $totalLigne = $ligne['produit']->getPrix() * $ligne['quantite'];//on multiplie le prix du produit par sa quantité
            $total += $totalLigne;//et on incrémente le total
        }

        return $total;//on retourne le total
    }
}