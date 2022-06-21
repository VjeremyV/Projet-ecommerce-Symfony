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
    /**
     * page d'ajout d'un fournisseur et de liste des fournisseurs
     *
     * @param FournisseurRepository $fournisseurRepository
     * @param Request $request
     * @param string $DirUpFour
     * @return void
     */
    #[Route('/admin/fournisseurs/add', name: 'app_admin_fournisseurs')]
    public function indexUp(FournisseurRepository $fournisseurRepository, Request $request, string $DirUpFour)
    {
        //on vérifie la query string pour voir si il y a une recherche
        $searchFournisseur = $request->query->get('search');
        //on instancie un nouveau fournisseur
        $fournisseur = new Fournisseur();
        //on créée le formulaire de création de fournisseur
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);

        //si il est soumis on récupère les données de la requête
        $form->handleRequest($request);
        //si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //si il y a une photo de soumise
            if ($photo = $form['image']->getData()) {
                //on randomise le nom du fichier, ->guessExtension() sert à récuperer l'extension
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    //on déplace le ficheir dans notre dossier dédié
                    $photo->move($DirUpFour, $filename);
                } catch (FileException $e) {
                    //si ça ne marche pas on attrape l'erreur et on l'affiche
                    var_dump($e);
                }
                //on definit l'image du fournisseur avec l'image récupérée
                $fournisseur->setImage($filename);
            }
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le fournisseur a bien été ajouté');
            //on ajoute le fournisseur en bdd
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

    /**
     * modification d'un fournisseur
     */
    #[Route('/admin/fournisseurs/update/{id}', name: 'update_fournisseur')]
    public function updateFournisseur(Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository, Request $request, string $DirUpFour, string $DirFour)
    {
        //on créée le formulaire de modification
        $form = $this->createForm(AddFournisseurFormType::class, $fournisseur);
        //si il est soumis on récupère les données de la requête
        $form->handleRequest($request);
        //si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //si il y a une photo de soumise
            if ($photo = $form['image']->getData()) {
                //on randomise le nom du fichier, ->guessExtension() sert à récuperer l'extension
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    //on déplace le ficheir dans notre dossier dédié
                    $photo->move($DirUpFour, $filename);
                } catch (FileException $e) {
                    //si ça ne marche pas on attrape l'erreur et on l'affiche
                    var_dump($e);
                }
                //on definit l'image du fournisseur avec l'image récupérée
                $fournisseur->setImage($filename);
            }
            //on ajoute un message flash avec le flag 'info'
            $this->addFlash('info', 'Le fournisseur a bien été modifiée');
            //on modifie le fournisseur en bdd
            $fournisseurRepository->add($fournisseur, true);
            //on redirige sur la même page
            return $this->redirectToRoute('update_fournisseur', ['id' => $fournisseur->getId()]);
        }
        return $this->render('admin_fournisseur/modify.html.twig', [
            'formFournisseur' => $form->createView(),
            'fournisseur' => $fournisseur,
            'dir' => $DirFour
        ]);
    }

    /**
     * url de suppression
     */
    #[Route('/admin/fournisseurs/delete/{id}', name: 'delete_fournisseur')]
    public function delFournisseur(FournisseurRepository $FournisseurRepository, Fournisseur $Fournisseur): Response
    {
        //on supprime le fournisseur en bdd
        $FournisseurRepository->remove($Fournisseur, true);
        //on ajoute un message flash avec le flag 'info'
        $this->addFlash('info', 'Le fournisseur a bien été supprimée');
        //on redirige vers la page de liste des fournisseurs
        return $this->redirectToRoute('app_admin_fournisseurs');
    }
}
