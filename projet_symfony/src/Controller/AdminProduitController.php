<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitAddFormType;
use App\Form\ProduitEditCaracFormType;
use App\Repository\GroupProduitRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProduitController extends AbstractController
{
    /**
     * page ajout d'un produit
     *
     * @param ProduitRepository $produitRepository
     * @param Request $request
     * @param string $ProduitUpDir
     * @return Response
     */
    #[Route('/admin/produit/add', name: 'admin_produit_add')]
    public function index(ProduitRepository $produitRepository, Request $request, string $ProduitUpDir): Response
    {
        //on instancie un nouveau produit
        $produit = new Produit;
        //on créée le formulaire associé
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        //si soumis on récupère la requête
        $form->handleRequest($request);
        //si valide et soumis
        if ($form->isSubmitted() && $form->isValid()) {
            //si il y a une photo de soumise
            if ($photo = $form['image']->getData()) {
                //on randomise le nom du fichier
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    //on bouge le fichier dans notre dossier dédié
                    $photo->move($ProduitUpDir, $filename);
                } catch (FileException $e) {
                    //on renvoie l'erreur
                    var_dump($e);
                }
                //on définit l'image du produit avec le fichier envoyé
                $produit->setImage($filename);
            }
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le produit a bien été ajoutée');
            //on ajoute le produit en bdd
            $produitRepository->add($produit, true);
            //on redirige vers la liste des produits
            return $this->redirectToRoute('admin_produit_updateListe');
        }
        return $this->render('admin_produit/add_produit.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * page de listing des produits
     *
     * @param ProduitRepository $produitRepository
     * @param Request $request
     * @param string $nom_search
     * @return Response
     */
    #[Route('/admin/produit/update{nom_search}', name: 'admin_produit_updateListe')]
    public function indexup(ProduitRepository $produitRepository, Request $request, $nom_search = ''): Response
    {
        //on verifie la query strign pour voir si il y a une recherche
        $search = $request->query->get('search');
        //on définit une variable options qui contiendra nos options de filtre
        $options = [];
        //pour chaque filtre on verifie si il existe une query string correspondante et si oui, on l'ajoute à options
        if ($nom_search = $request->query->get('nom_search')) {
            $options['nom_search'] = $nom_search;
        }
        if ($categorie_search = $request->query->get('categorie_search')) {
            $options['categorie_search'] = $categorie_search;
        }
        if ($four_search = $request->query->get('four_search')) {
            $options['four_search'] = $four_search;
        }
        if ($actif_search = $request->query->get('actif_search')) {
            $options['actif_search'] = $actif_search;
        }
        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getPaginator($offset, $search, $options);
        $nbrePages = ceil(count($paginator) / ProduitRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / ProduitRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;
        return $this->render('admin_produit/update_produit_list.html.twig', [
            'listProduits' => $paginator,
            'search' => $search,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'offset' => ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    /**
     * page d'édition d'un produit
     *
     * @param Produit $produit
     * @param ProduitRepository $produitRepository
     * @param Request $request
     * @param string $ProduitUpDir
     * @param string $ProduitDir
     * @param GroupProduitRepository $groupProduitRepository
     * @return Response
     */
    #[Route('/admin/produit/update/{id}', name: 'update_produit')]
    public function updateProduit(Produit $produit, ProduitRepository $produitRepository, Request $request, string $ProduitUpDir, string $ProduitDir, GroupProduitRepository $groupProduitRepository): Response
    {
        //on créée le formulaire d'édition
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        //on créée le formulaire pour choisir les caracteristiques du produit
        $form2 = $this->createForm(ProduitEditCaracFormType::class, $produit, ['data' => $produit]);

        //si form est soumis on récupère la request
        $form->handleRequest($request);
        //si valide et soumis
        if ($form->isSubmitted() && $form->isValid()) {
            //si on a une phot de soumise
            if ($photo = $form['image']->getData()) {
                //onrandomise le nom du fichier
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    //on deplace le fichier dans notre dossier dédié
                    $photo->move($ProduitUpDir, $filename);
                } catch (FileException $e) {
                    var_dump($e);
                    // on affiche l'erreur
                }
                //on définit l'image du produit avec le fichier 
                $produit->setImage($filename);
            }
            //on modifie le produit en base
            $produitRepository->add($produit, true);
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le produit a bien été modifié');
            //on redirige sur la même page
            return $this->redirectToRoute('update_produit', ['id' => $produit->getId()]);
        }
        //si form2 est soumis on récupère la request
        $form2->handleRequest($request);
        //si valide et soumis
        if ($form2->isSubmitted() && $form2->isValid()) {
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le produit a bien été modifié');
            //on modifie le produit en base
            $produitRepository->add($produit, true);
            //on redirige sur la même page
            return $this->redirectToRoute('update_produit', ['id' => $produit->getId()]);
        }
        return $this->render('admin_produit/update_produit.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'produit' => $produit,
            'dir' => $ProduitDir
        ]);
    }

    /**
     * url de suppression de produit
     *
     * @param ProduitRepository $produitRepository
     * @param Produit $produit
     * @return Response
     */
    #[Route('/admin/produit/delete/{id}', name: 'delete_produit')]
    public function deleteProduit(ProduitRepository $produitRepository, Produit $produit): Response
    {
        //on supprime le produit en base
        $produitRepository->remove($produit, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'Le produit a bien été supprimée');
        //on redirige vers la liste des produits
        return $this->redirectToRoute('admin_produit_updateListe');
    }
}
