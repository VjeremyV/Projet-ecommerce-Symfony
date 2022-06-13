<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitAddFormType;
use App\Form\ProduitEditCaracFormType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProduitController extends AbstractController
{
    #[Route('/admin/produit/add', name: 'admin_produit_add')]
    public function index(ProduitRepository $produitRepository, Request $request,string $ProduitUpDir): Response
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($photo = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($ProduitUpDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $produit->setImage($filename);
                $produitRepository->add($produit, true);
            }
            
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
    public function updateProduit(Produit $produit, ProduitRepository $produitRepository, Request $request, string $ProduitUpDir, string $ProduitDir): Response
    {
        $form = $this->createForm(ProduitAddFormType::class, $produit);
        $form2 = $this->createForm(ProduitEditCaracFormType::class, $produit, ['data' => $produit]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($photo = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($ProduitUpDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $produit->setImage($filename);
                $produitRepository->add($produit, true);
            }

            $this->addFlash('info', 'Le produit a bien été modifié');
            $produitRepository->add($produit, true);
            return $this->redirectToRoute('update_produit', ['id' => $produit->getId()]);
        }
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $this->addFlash('info', 'Le produit a bien été modifié');
            $produitRepository->add($produit, true);
            return $this->redirectToRoute('update_produit', ['id' => $produit->getId()]);
        }
        return $this->render('admin_produit/update_produit.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'produit' => $produit,
            'dir' => $ProduitDir
        ]);
    }

    #[Route('/admin/produit/delete/{id}', name: 'delete_produit')]
    public function deleteProduit(ProduitRepository $produitRepository, Produit $produit): Response
    {
        $produitRepository->remove($produit, true);
        $this->addFlash('info', 'Le produit a bien été supprimée');
        return $this->redirectToRoute('admin_produit_updateListe');
    }
}
