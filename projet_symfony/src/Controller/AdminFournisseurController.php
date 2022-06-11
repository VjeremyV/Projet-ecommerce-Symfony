<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFournisseurController extends AbstractController
{
    #[Route('/admin/fournisseur', name: 'app_admin_fournisseur')]
    public function index(FournisseurRepository $fournisseurRepo): Response
    {
        return $this->render('admin_fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepo->findAll(),
        ]);
    }

    #[Route('/admin/fournisseur/add_fournisseur', name: 'app_admin_fournisseur_Add')]
    public function newFournisseur(Fournisseur $fournisseur, Request $request): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);
        return $this->render('admin_fournisseur/newfournisseur.html.twig', [
            'fournisseur' => $fournisseur,
            'form_add_fournisseur' => $form->createView()
        ]);
    }
}
