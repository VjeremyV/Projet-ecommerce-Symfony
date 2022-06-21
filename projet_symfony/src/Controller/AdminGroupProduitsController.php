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
    /**
     * page de création de groupes produits
     *
     * @param Request $request
     * @param GroupProduitRepository $groupProduitRepository
     * @return Response
     */
    #[Route('/admin/group_produits/', name: 'app_add_group_produits')]
    public function addGroupProduit(Request $request, GroupProduitRepository $groupProduitRepository): Response
    {
        //on instancie un nouveau groupe de produits
        $groupProduit = new GroupProduit;

        //on créée le formulaire de création
         $form = $this->createForm(AddCategorieProduitFormType::class, $groupProduit);
        //si il est soumis on récupère les données de la requête
         $form->handleRequest($request);
        //si il est soumis et valide
         if ($form->isSubmitted() && $form->isValid()) {
            //on ajoute un message flash avec le flag 'info'
             $this->addFlash('info', 'Le groupement de produit a bien été ajouté');
             //on ajoute le group de produit en bdd
             $groupProduitRepository->add($groupProduit, true);
             //on redirige sur cette même page
             return $this->redirectToRoute('app_add_group_produits');
         }

        return $this->render('admin_produit/add_group_produits.html.twig', [
        'form'=> $form->createView()
        ]);
    }
}
