<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitAddFormType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProduitController extends AbstractController
{
    #[Route('/admin/produit/add', name: 'admin_produit_add')]
    public function index(ProduitRepository $produitRepository, Request $request): Response
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Le produit a bien été ajoutée');
            $produitRepository->add($produit, true);
            return $this->redirectToRoute('admin_produit_updateListe');
        }
        return $this->render('admin_produit/add_produit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/produit/update', name: 'admin_produit_updateListe')]
    public function indexup(ProduitRepository $produitRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        $options = [];
        if($nom_search = $request->query->get('nom_search')){
            $options['nom_search'] = $nom_search;
        }
        if($categorie_search = $request->query->get('categorie_search')){
            $options['categorie_search'] = $categorie_search;
        }
        if($four_search = $request->query->get('four_search')){
            $options['four_search'] = $four_search;
        }
        if($actif_search = $request->query->get('actif_search')){
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

    #[Route('/admin/produit/update/{id}', name: 'update_produit')]
    public function updateProduit(): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
        ]);
    }

    #[Route('/admin/produit/delete', name: 'admin_produit_deleteListe')]
    public function deleteListeProduit(): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
        ]);
    }
    #[Route('/admin/produit/delete', name: 'delete_produit')]
    public function deleteProduit(): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
        ]);
    }
}
