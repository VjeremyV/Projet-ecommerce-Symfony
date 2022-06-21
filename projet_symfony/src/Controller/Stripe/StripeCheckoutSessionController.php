<?php

namespace App\Controller\Stripe;

use App\Controller\PageController;
use App\Entity\Commandes;
use App\Entity\Contenu;
use App\Repository\CategoriesRepository;
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
        // on récupère le contenu complet du panier dans une variable (voir le service panier)
        $lignesP = $panier->getFullPanier();

        // on créée une variable tableau vide qui contiendra nos produits
        $produits = [];

        //on définit notre clé stripe avec la class stripe (la clé est définis dans le .env.local et configurée dans le service.yaml)
        Stripe::setApiKey($stripeSK);

        // on instancie un objet StripeClient
        $stripe = new \Stripe\StripeClient($stripeSK);

        //Pour chaque ligne de produit du panier
        foreach ($lignesP as $ligne) {
            //on créée une ligne produit de "type" stripe 
            $product = $stripe->products->create(['name' => $ligne['produit']->getNom()]);

            //on attribu à ce produit un prix de 'type' stripe 
            $price = \Stripe\Price::create([
                'product' => $product['id'], //on lie les 2 entités produits et prix par l'id du produit
                'unit_amount' => ($ligne['produit']->getPrix()) * 100, //montant en centimes, on multiplie par 100 car nos prix en bdd sont en euros avec 2 chiffres après la virgule
                'currency' => 'eur', //la devise
            ]);
            $produits[] = ['price' => $price['id'], 'quantity' => $ligne['quantite']]; // on ajoute un élément au tableau produits
        }

        $session = Session::create([ //on créée une session stripe

            'line_items' => [$produits], //on y ajoute nos lignes de produits contenu dans la variable produit, il s'agit d'un tableau de tableaux
            'mode' => 'payment', //on choisi le mode, pour nous ici c'est un paiement simple donc 'payment'
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL), //url vers laquelle on sera renvoyé en cas de succés
            'cancel_url' => $this->generateUrl('cancel', [], UrlGeneratorInterface::ABSOLUTE_URL), //url vers laquelle on sera renvoyé en cas d'annulation
        ]);

        return $this->redirect($session->url, 303); //redirection vers le service de stripe pour le paiement
    }


    /**
     * url en cas de succés
     *
     * @param ProduitRepository $produitRepository
     * @param CategoriesRepository $categoriesRepository
     * @param CommandesRepository $commandesRepository
     * @param Panier $panier
     * @param ContenuRepository $contenuRepository
     * @param RequestStack $session
     * @return Response
     */
    #[Route('/success-url', name: 'success_url')]
    public function successUrl(ProduitRepository $produitRepository, CategoriesRepository $categoriesRepository, CommandesRepository $commandesRepository, Panier $panier, ContenuRepository $contenuRepository, RequestStack $session): Response
    {
        //on récupère nos catégories pour l'affichage du menu
        $categories = PageController::Menu($categoriesRepository);

        //on instancie une nouvelle commande
        $commandes = new Commandes;
        //on récupère le client courant, $this->getUser() nous renvoie l'admin courant auquel on fait un getClient()
        $user = $this->getUser()->getClient();
        //on récupère la date du jour au bon format
        $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        //on définit les valeurs de la commande
        $commandes->setClient($user)->setMontant($panier->getTotal())->setIsPanier(0)->setCreatedAt($date);
        //on ajoute la commande en base
        $commandesRepository->add($commandes, true);

        //on récupère le contenu complet du panier dans une variable (voir le service panier)
        $lignesP = $panier->getFullPanier();

        //pour chaque ligne de produit du panier
        foreach ($lignesP as $ligne) {

            //on instancie un nouveau contenu
            $contenu = new Contenu;
            // on lui attribue les valeurs correspondantes à la ligne en cours
            $contenu->setCommandes($commandes)->setProduits($ligne['produit'])->setPrix($ligne['produit']->getPrix())->setQuantite($ligne['quantite']);
            // on ajoute la ligne en base
            $contenuRepository->add($contenu, true);

            //on récupère le produit courante avec l'id
            $produit = $produitRepository->find($ligne['produit']->getId());
            //on récupère le stock courant
            $stockA = $produit->getStock();
            //on met à jour le stock
            $produit->setStock($stockA - $ligne['quantite']);
            // on met à jour en bdd
            $produitRepository->add($produit, true);
        }
        //on reinitialise le panier à un tableau vide
        $session->getSession()->set('panier', []);

        return $this->render('front/passer_commande/reussi.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * url en cas d'erreur ou d'annulation
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/cancel-url', name: 'cancel')]
    public function cancelUrl(CategoriesRepository $categoriesRepository): Response
    {
        //on récupère nos catégories pour l'affichage du menu
        $categories = PageController::Menu($categoriesRepository);

        return $this->render('front/passer_commande/cancel.html.twig', [
            'categories' => $categories,
        ]);
    }
}
