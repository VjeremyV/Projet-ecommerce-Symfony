<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Clients;
use App\Form\SigninUserFormType;
use App\Repository\AdminRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/inscription', name: 'app_signin')]
    public function signIn(CategoriesRepository $categoriesRepository,ClientsRepository $clientsRepository, Request $request, AdminRepository $adminRepository): Response
    {   //    pour le menu
        $getcaract = $categoriesRepository->getListCategories();
        //
        $user = new Clients;
        $form = $this->createForm(SigninUserFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form['MDP']->getData() === $form['Confirmmdp']->getData() ){
                $this->addFlash('info', 'Votre inscriptio s\'est bien passée');
    
                $adm = new admin;
                $adm->setPseudo($form['pseudo']->getData())->setPassword($form['MDP']->getData())->setRoles(['ROLE_USER']);
                // $adminRepository->add($adm);
                $user->setAdmin($adm);
    
                $clientsRepository->add($user, true);
                return $this->redirectToRoute('app_signin');
            } else {
                $this->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                echo'prout';
            } 
        }
        return $this->render('security/signin.html.twig', [
            'form'=> $form->createView(),
            'getcaract'=> $getcaract
        ]);
    }
}
