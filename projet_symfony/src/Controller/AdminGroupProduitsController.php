<?php

namespace App\Controller;

use App\Entity\GroupProduit;
use App\Form\AddCategorieProduitFormType;
use App\Repository\GroupProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGroupProduitsController extends AbstractController
{

    #[Route('/admin/group_produits/', name: 'app_add_group_produits')]
    public function addGroupProduit(Request $request, GroupProduitRepository $groupProduitRepository): Response
    {
        $groupProduit = new GroupProduit;

         $form = $this->createForm(AddCategorieProduitFormType::class, $groupProduit);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $this->addFlash('info', 'Le groupement de produit a bien été ajouté');
             $groupProduitRepository->add($groupProduit, true);
             return $this->redirectToRoute('app_add_group_produits');
         }

        return $this->render('admin_produit/add_group_produits.html.twig', [
        'form'=> $form->createView()
        ]);
    }
}
