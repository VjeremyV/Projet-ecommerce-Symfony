<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\PasserCommandeFormType;
use App\Repository\CategoriesRepository;
use App\Services\InfosUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasserCommandeController extends AbstractController
{
    #[Route('/passer/commande/{id}', name: 'app_passer_commande')]
    /**
     * page de passage d'une commande
     *
     * @param Request $request
     * @param Admin $admin
     * @param CategoriesRepository $categoriesRepository
     * @param InfosUtilisateur $infosUtilisateur
     * @return Response
     */
    public function index(Request $request,Admin $admin,CategoriesRepository $categoriesRepository, InfosUtilisateur $infosUtilisateur): Response
    {
        //on récupère le client
        $clients = $admin->getClient();
        //on récupère les catégories pour l'affiche du menu
        $categories = PageController::Menu($categoriesRepository);

        //on créée le formulaire de modification des données
        $form = $this->createForm(PasserCommandeFormType::class, $clients);

        //on récupère les données du client avant modificatiob
        $tel = $clients->getTelephone();
        $mail = $clients->getAdresseMail();
        $nom = $clients->getNom();
        $prenom = $clients->getPrenom();
        $adresse = $clients->getAdresse();
        $cp = $clients->getCodePostale();
        $ville = $clients->getVille();
        
        //si le formulaire est soumis on récupère la request
        $form->handleRequest($request);
        //si soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
                //VOIR LES METHODES DANS LE SERVICE InfosUtilisateur

                  //si le telephone est renseigné
                  $infosUtilisateur->checkTelephone($form, $clients, $tel);
                  //si l'adresse mail est renseignée
                  $infosUtilisateur->checkMail($form, $clients, $mail);
                  //si le nom est renseigné
                  $infosUtilisateur->checkNom($form, $clients, $nom);
                  //si l'adresse est renseignée
                  $infosUtilisateur->checkAdresse($form, $clients, $adresse);
                  //si le prénom est renseigné
                  $infosUtilisateur->checkPrenom($form, $clients, $prenom);
                  //si le code postal est renseigné
                  $infosUtilisateur->checkCp($form, $clients, $cp);
                  //si la ville est renseignée
                  $infosUtilisateur->checkVille($form, $clients, $ville);
        }
        return $this->render('front/passer_commande/index.html.twig', [
            'categories'=> $categories,
            'form'=>$form->createView()
        ]);
    }
}
