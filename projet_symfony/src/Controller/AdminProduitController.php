<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitAddFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProduitController extends AbstractController
{
    #[Route('/admin/produit/add', name: 'admin_produit_add')]
    public function index(): Response
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        
        return $this->render('admin_produit/add_produit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/produit/update', name: 'admin_produit_updateListe')]
    public function indexup(): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
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
