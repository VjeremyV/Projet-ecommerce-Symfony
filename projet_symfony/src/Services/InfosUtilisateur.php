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
    public function checkTelephone($form, $clients, $tel)
    {
        if ($form['telephone']->getData() && $form['telephone']->getData() !== $tel) {
            if (count($this->clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) > 0) {
                $this->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
            } else {
                //si le telephone est unique en bdd
                $this->addFlash('info', 'Le numéro de téléphone a bien été mis à jour');
                $this->clientsRepository->add($clients, true); //on envoie sur la bdd
            }
        }
    }
    public function checkMail($form, $clients, $mail)
    {
        if ($form['adresseMail']->getData() && $form['adresseMail']->getData() !== $mail) {
            if (count($this->clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) > 0) {

                $this->addFlash('error', 'L\'adresse Mail est déjà utilisée');
            } else {
                // si le mail est unique en bdd
                $this->addFlash('info', 'L\'adresse mail a bien été mise à jour');
                $this->clientsRepository->add($clients, true); //on envoie sur la bdd
            }
        }
    }
    public function checkNom($form, $clients, $nom)
    {
        if ($form['nom']->getData() && $form['nom']->getData() !== $nom) {
            $this->addFlash('info', 'Votre nom a bien modifié');
            $this->clientsRepository->add($clients, true); //on envoie sur la bdd
        }
    }
    public function checkAdresse($form, $clients, $adresse)
    {
        if ($form['adresse']->getData() && $form['adresse']->getData() !== $adresse) {
            $this->addFlash('info', 'Votre adresse a bien modifiée');
            $this->clientsRepository->add($clients, true); //on envoie sur la bdd
        }
    }
    public function checkPrenom($form, $clients, $prenom)
    {
        if ($form['prenom']->getData() && $form['prenom']->getData() !== $prenom) {
            $this->addFlash('info', 'Le prénom a bien été modifié');
            $this->clientsRepository->add($clients, true); //on envoie sur la bdd
        }
    }
    public function checkCp($form, $clients, $cp)
    {
        if ($form['codePostale']->getData() && $form['codePostale']->getData() !== $cp) {
            $this->addFlash('info', 'Le code postal a bien été mis à jour');
            $this->clientsRepository->add($clients, true); //on envoie sur la bdd
        }
    }
    public function checkVille($form, $clients, $ville)
    {
        if ($form['ville']->getData() && $form['ville']->getData() !== $ville) {
            $this->addFlash('info', 'La ville a bien été mise à jour');
            $this->clientsRepository->add($clients, true); //on envoie sur la bdd
        }
    }
}
