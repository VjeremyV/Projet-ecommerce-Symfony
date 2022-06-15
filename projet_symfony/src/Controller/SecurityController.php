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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function signIn(UserPasswordHasherInterface $hashedPwd, CategoriesRepository $categoriesRepository, ClientsRepository $clientsRepository, Request $request, AdminRepository $adminRepository): Response
    {   //    on va chercher les catégories à afficher dans le menu
        $getCategories = PageController::Menu($categoriesRepository);
        // on instancie un nouveau client et on créée un formulaire
        $user = new Clients;
        $form = $this->createForm(SigninUserFormType::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //si le formulaire est soumis et valide
            if (!$adminRepository->findBy(['pseudo' => $form['pseudo']->getData()])) {
                //si le pseudo est unique en bdd
                if (!$clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) {
                    //si le telephone est unique en bdd
                    if (!$clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) {
                        // si le mail est unique en bdd
                        if ($form['MDP']->getData() === $form['Confirmmdp']->getData()) {
                            //si les 2 mot de passe rentrés sont égaux
                            $exp = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/'; // on prépare une regex
                            if (preg_match($exp, $form['MDP']->getData())) {
                                //si le mdp correspond à la regex
                                $this->addFlash('info', 'Votre inscription s\'est bien passée');// on ajoute un message de reussite

                                $adm = new admin;//on instancie un nouvel admin
                                $pwd = $hashedPwd->hashPassword($adm, $form['MDP']->getData());//on hash le mdp
                                $adm->setPseudo($form['pseudo']->getData())->setPassword($pwd)->setRoles(['ROLE_USER']);//on définit les valeurs de Admin avec les champs du formulaire
                                $user->setAdmin($adm); // on définit le champs Admin de user avec notre objet admin

                                $clientsRepository->add($user, true);//on envoie sur la bdd
                                return $this->redirectToRoute('app_signin');// on redirige sur la même page
                            } else {
                                $this->addFlash('error', 'Le mot de passe doit contenir au moins 10 caractères, 1minuscule, 1 majuscule, 1caractère spécial et 1 chiffre');
                            }
                        } else {
                            $this->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                        }
                    } else {
                        $this->addFlash('error', 'L\'adresse Mail est déjà utilisée');
                    }
                } else {
                    $this->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
                }
            } else {
                $this->addFlash('error', 'Le pseudo est déjà utilisé');
            }
        }
        return $this->render('security/signin.html.twig', [
            'form' => $form->createView(),
            'categories' => $getCategories,
        ]);
    }
}
