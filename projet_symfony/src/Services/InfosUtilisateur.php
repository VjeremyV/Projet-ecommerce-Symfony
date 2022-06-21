<?php

namespace App\Services;

use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InfosUtilisateur extends AbstractController
{
    protected $clientsRepository;
    public function __construct(ClientsRepository $clientsRepository)
    {
        $this->clientsRepository = $clientsRepository;
    }
    /**
     * verifie et met à jour données telephone
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $tel
     * @return void
     */
    public function checkTelephone($form, $clients, $tel)
    {
        //si il y a une valeur tel dans le formulaire et qu'il est different du tel existant
        if ($form['telephone']->getData() && $form['telephone']->getData() !== $tel) {
            //si le telephone n'est pas unique en bdd
            if (count($this->clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) > 0) {
                //on ajoute un message flash d'erreur avec le flag 'error'
                $this->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
            } else {
                //si le telephone est unique en bdd
                $this->addFlash('info', 'Le numéro de téléphone a bien été mis à jour');
                //on envoie sur la bdd
                $this->clientsRepository->add($clients, true); 
            }
        }
    }
    public function checkMail($form, $clients, $mail)
    {
        //si il y a une valeur mail dans le formulaire et qu'il est different du mail existant
        if ($form['adresseMail']->getData() && $form['adresseMail']->getData() !== $mail) {
            //si le mail n'est pas unique en bdd
            if (count($this->clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) > 0) {
                //on ajoute un message flash d'erreur avec le flag 'error'
                $this->addFlash('error', 'L\'adresse Mail est déjà utilisée');
            } else {
                // si le mail est unique en bdd
                $this->addFlash('info', 'L\'adresse mail a bien été mise à jour');
                //on envoie sur la bdd
                $this->clientsRepository->add($clients, true); 
            }
        }
    }
    /**
     * verifie le nom dans un formulaire
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $nom
     * @return void
     */
    public function checkNom($form, $clients, $nom)
    {
        //si le nom est renseigné et s'il est différent du nom actuel
        if ($form['nom']->getData() && $form['nom']->getData() !== $nom) {
            $this->addFlash('info', 'Votre nom a bien modifié');
            //on envoie sur la bdd
            $this->clientsRepository->add($clients, true); 
        }
    }
    /**
     * vérifie l'adresse dans un formulaire
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $adresse
     * @return void
     */
    public function checkAdresse($form, $clients, $adresse)
    {
        //si l'aresse est renseignée et si elle est différente de l'adresse actuelle
        if ($form['adresse']->getData() && $form['adresse']->getData() !== $adresse) {
            $this->addFlash('info', 'Votre adresse a bien modifiée');
            //on envoie sur la bdd
            $this->clientsRepository->add($clients, true); 
        }
    }
    /**
     * vérifie le prénom dans un formulaire
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $prenom
     * @return void
     */
    public function checkPrenom($form, $clients, $prenom)
    {
        //si le prénom est renseigné et s'il est différent du prénom actuel
        if ($form['prenom']->getData() && $form['prenom']->getData() !== $prenom) {
            $this->addFlash('info', 'Le prénom a bien été modifié');
            //on envoie sur la bdd
            $this->clientsRepository->add($clients, true); 
        }
    }
    /**
     * vérifie le code postale dans un formulaire
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $cp
     * @return void
     */
    public function checkCp($form, $clients, $cp)
    {
        //si le CP est renseigné et s'il est différent du CP actuel
        if ($form['codePostale']->getData() && $form['codePostale']->getData() !== $cp) {
            $this->addFlash('info', 'Le code postal a bien été mis à jour');
            //on envoie sur la bdd
            $this->clientsRepository->add($clients, true); 
        }
    }
    /**
     * vérifie la ville dans le formulaire
     *
     * @param [type] $form
     * @param [type] $clients
     * @param [type] $ville
     * @return void
     */
    public function checkVille($form, $clients, $ville)
    {
        //si la ville est renseignée et si elle est différente de la ville actuelle
        if ($form['ville']->getData() && $form['ville']->getData() !== $ville) {
            $this->addFlash('info', 'La ville a bien été mise à jour');
            //on envoie sur la bdd
            $this->clientsRepository->add($clients, true); 
        }
    }
}
