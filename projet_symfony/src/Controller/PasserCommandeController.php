<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Clients;
use App\Form\PasserCommandeFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ClientsRepository;
use App\Services\InfosUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasserCommandeController extends AbstractController
{
    #[Route('/passer/commande/{id}', name: 'app_passer_commande')]
    public function index(Request $request,Admin $admin,CategoriesRepository $categoriesRepository, InfosUtilisateur $infosUtilisateur): Response
    {
        $clients = $admin->getClient();
        $categories = PageController::Menu($categoriesRepository);


        $form = $this->createForm(PasserCommandeFormType::class, $clients);

        $tel = $clients->getTelephone();
        $mail = $clients->getAdresseMail();
        $nom = $clients->getNom();
        $prenom = $clients->getPrenom();
        $adresse = $clients->getAdresse();
        $cp = $clients->getCodePostale();
        $ville = $clients->getVille();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                  //si le telephone est renseigné
                  $infosUtilisateur->checkTelephone($form, $clients, $tel);
                  //si l'adresse mail est renseignée
                  $infosUtilisateur->checkMail($form, $clients, $mail);
                  //si le nom est renseigné
                  $infosUtilisateur->checkNom($form, $clients, $nom);
                  //si le prénom est renseigné
                  //si l'adresse est renseignée
                  $infosUtilisateur->checkAdresse($form, $clients, $adresse);
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

    #[Route('/commande/validation/{id}', name: 'app_validation_commande')]
    public function commandeValidee(CategoriesRepository $categoriesRepository){
        $categories = PageController::Menu($categoriesRepository);

        return $this->render('front/passer_commande/index.html.twig', [
            'categories'=> $categories,
        ]);
    }
}
