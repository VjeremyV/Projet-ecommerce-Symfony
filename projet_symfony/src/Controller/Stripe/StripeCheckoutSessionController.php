<?php

namespace App\Controller\Stripe;

use App\Controller\PageController;
use App\Entity\Clients;
use App\Entity\Commandes;
use App\Entity\Contenu;
use App\Repository\CategoriesRepository;
use App\Repository\ClientsRepository;
use App\Repository\CommandesRepository;
use App\Repository\ContenuRepository;
use App\Repository\ProduitRepository;
use App\Services\Panier;
use DateTimeImmutable;
use DateTimeZone;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeCheckoutSessionController extends AbstractController
{

    #[Route('/checkout', name: 'checkout')]
    public function index($stripeSK, Panier $panier)
    {
        $lignesP = $panier->getFullPanier();
        $produits = [];
        Stripe::setApiKey($stripeSK);


        $stripe = new \Stripe\StripeClient($stripeSK);
        foreach ($lignesP as $ligne) {
            $product = $stripe->products->create(['name' => $ligne['produit']->getNom()]);
            $price = \Stripe\Price::create([
                'product' => $product['id'],
                'unit_amount' => ($ligne['produit']->getPrix()) * 100,
                'currency' => 'eur',
            ]);
            $produits[] = ['price' => $price['id'], 'quantity' => $ligne['quantite']];
        }

        // dd($price);
        $session = Session::create([

            'line_items' => [$produits],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);


        // dd($session);
        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(ProduitRepository $produitRepository,CategoriesRepository $categoriesRepository, CommandesRepository $commandesRepository, Panier $panier, ContenuRepository $contenuRepository, RequestStack $session): Response
    {
        $categories = PageController::Menu($categoriesRepository);

        $commandes = new Commandes;
        $user = $this->getUser()->getClient();
        $date= new DateTimeImmutable('now',  new DateTimeZone('Europe/Paris'));
        $commandes->setClient($user)->setMontant($panier->getTotal())->setIsPanier(0)->setCreatedAt($date);
        $commandesRepository->add($commandes, true);

        $lignesP = $panier->getFullPanier();
        foreach ($lignesP as $ligne) {

            $contenu = new Contenu;
            $contenu->setCommandes($commandes)->setProduits($ligne['produit'])->setPrix($ligne['produit']->getPrix())->setQuantite($ligne['quantite']);
            $contenuRepository->add($contenu, true);

            $produit = $produitRepository->find($ligne['produit']->getId());
            $stockA = $produit->getStock();
            $produit->setStock($stockA - $ligne['quantite']);
            $produitRepository->add($produit, true);
        }
        $session->getSession()->set('panier', []);
        
        return $this->render('front/passer_commande/reussi.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/cancel-url', name: 'cancel')]
    public function cancelUrl(CategoriesRepository $categoriesRepository): Response
    {
        $categories = PageController::Menu($categoriesRepository);

        return $this->render('front/passer_commande/cancel.html.twig', [
            'categories' => $categories,
        ]);
    }
}
