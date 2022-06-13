<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\AddFournisseurFormType;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFournisseurController extends AbstractController
{
    #[Route('/admin/fournisseurs/add', name: 'app_admin_fournisseurs_add')]
    public function AddFournisseur(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);
        $getFounisseur = $fournisseurRepository -> getListFournisseurs();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Le fournisseur a bien été ajouté');
            $fournisseurRepository->add($fournisseur, true);
            return $this->redirectToRoute('app_admin_fournisseurs_add');
        }
        return $this->render('admin_fournisseur/index.html.twig', [
            'formFournisseur' => $form->createView(),
            'getFournisseurs' => $getFounisseur,
        ]);
    }

    #[Route('/admin/fournisseurs/update', name: 'fournisseurs_update_list')]
    public function indexUp(FournisseurRepository $fournisseurRepository, Request $request)
    {
        $searchFournisseur = $request->query->get('search');

        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $fournisseurRepository->getPaginator($offset, $searchFournisseur);
        $nbrePages = ceil(count($paginator) / FournisseurRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + FournisseurRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / FournisseurRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_fournisseur/update_list.html.twig', [
            'listFournisseurs' => $paginator,
            'searchFournisseur' => $searchFournisseur,
            'previous' => $offset - FournisseurRepository::PAGINATOR_PER_PAGE,
            'offset' => FournisseurRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
        ]);
    }

    #[Route('/admin/fournisseurs/update/{id}', name: 'update_fournisseur')]
    public function updateFournisseur(Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository, Request $request)
    {
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('info', 'Le fournisseur a bien été modifiée');
            $fournisseurRepository->add($fournisseur, true);
            return $this->redirectToRoute('update_fournisseur', ['id' => $fournisseur -> getId()]);
        }
        return $this->render('admin_fournisseur/modify.html.twig', [
            'formFournisseur' => $form->createView(),
        ]);
    }

    #[Route('/admin/fournisseurs/delete/{id}', name: 'delete_fournisseur')]
    public function delFournisseur(FournisseurRepository $FournisseurRepository, Fournisseur $Fournisseur): Response
    {
        $FournisseurRepository->remove($Fournisseur, true);
        $this->addFlash('info', 'Le fournisseur a bien été supprimée');
        return $this->redirectToRoute('fournisseurs_update_list');
    }
}
