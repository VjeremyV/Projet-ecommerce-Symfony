<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\AddFournisseurFormType;
use App\Repository\FournisseurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminFournisseurController extends AbstractController
{
    #[Route('/admin/fournisseurs/add', name: 'app_admin_fournisseurs')]

    public function indexUp(FournisseurRepository $fournisseurRepository, Request $request, string $DirUpFour)
    {
        $searchFournisseur = $request->query->get('search');
        $fournisseur = new Fournisseur();
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($photo = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($DirUpFour, $filename);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $fournisseur->setImage($filename);
                $fournisseurRepository->add($fournisseur, true);
            }

            $this->addFlash('info', 'Le fournisseur a bien été ajouté');
            $fournisseurRepository->add($fournisseur, true);
            return $this->redirectToRoute('app_admin_fournisseurs');
        }

        //pagination
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $fournisseurRepository->getPaginator($offset, $searchFournisseur);
        $nbrePages = ceil(count($paginator) / FournisseurRepository::PAGINATOR_PER_PAGE);
        $next = min(count($paginator), $offset + FournisseurRepository::PAGINATOR_PER_PAGE);
        $pageActuelle = ceil($next / FournisseurRepository::PAGINATOR_PER_PAGE);
        $difPages = $nbrePages - $pageActuelle;

        return $this->render('admin_fournisseur/index.html.twig', [
            'listFournisseurs' => $paginator,
            'searchFournisseur' => $searchFournisseur,
            'previous' => $offset - FournisseurRepository::PAGINATOR_PER_PAGE,
            'offset' => FournisseurRepository::PAGINATOR_PER_PAGE,
            'next' => $next,
            'nbrePages' => $nbrePages,
            'pageActuelle' => $pageActuelle,
            'difPages' => $difPages,
            'formFournisseur' => $form->createView(),
        ]);
    }

    #[Route('/admin/fournisseurs/update/{id}', name: 'update_fournisseur')]
    public function updateFournisseur(Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository, Request $request, string $DirUpFour, string $DirFour)
    {
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            
            if ($photo = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($DirUpFour, $filename);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $fournisseur->setImage($filename);
                $fournisseurRepository->add($fournisseur, true);
            }
            $this->addFlash('info', 'Le fournisseur a bien été modifiée');
            $fournisseurRepository->add($fournisseur, true);
            return $this->redirectToRoute('update_fournisseur', ['id' => $fournisseur->getId()]);
        }
        return $this->render('admin_fournisseur/modify.html.twig', [
            'formFournisseur' => $form->createView(),
            'fournisseur' => $fournisseur,
            'dir' => $DirFour
        ]);
    }

    #[Route('/admin/fournisseurs/delete/{id}', name: 'delete_fournisseur')]
    public function delFournisseur(FournisseurRepository $FournisseurRepository, Fournisseur $Fournisseur): Response
    {
        $FournisseurRepository->remove($Fournisseur, true);
        $this->addFlash('info', 'Le fournisseur a bien été supprimée');
        return $this->redirectToRoute('app_admin_fournisseurs');
    }
}
