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
    /**
     * page de connexion
     * @param AuthenticationUtils $authenticationUtils
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CategoriesRepository $categoriesRepository): Response
    {
        //on récupère les catégories pour l'affichage du menu
        $getCategories = PageController::Menu($categoriesRepository);

        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'categories' => $getCategories
        ]);
    }

    /**
     * deconnexion
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        //Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * page inscription
     * @param UserPasswordHasherInterface $hashedPwd
     * @param CategoriesRepository $categoriesRepository
     * @param ClientsRepository $clientsRepository
     * @param Request $request
     * @param AdminRepository $adminRepository
     * @return Response
     */
    #[Route(path: '/inscription', name: 'app_signin')]
    public function signIn(UserPasswordHasherInterface $hashedPwd, CategoriesRepository $categoriesRepository, ClientsRepository $clientsRepository, Request $request, AdminRepository $adminRepository): Response
    {   //    on va chercher les catégories à afficher dans le menu
        $getCategories = PageController::Menu($categoriesRepository);
        // on instancie un nouveau client
        $user = new Clients;
        //on créée un formulaire pour l'inscription
        $form = $this->createForm(SigninUserFormType::class, $user);
        //si il est soumis on récupère les données de la requête
        $form->handleRequest($request);
        //si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //si le pseudo est unique en bdd
            if (!$adminRepository->findBy(['pseudo' => $form['pseudo']->getData()])) {
                //si le telephone est unique en bdd
                if (!$clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) {
                    // si le mail est unique en bdd
                    if (!$clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) {
                        //si les 2 mot de passe rentrés sont égaux
                        if ($form['MDP']->getData() === $form['Confirmmdp']->getData()) {
                            // on prépare une regex
                            $exp = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
                            //si le mdp correspond à la regex
                            if (preg_match($exp, $form['MDP']->getData())) {
                                // on ajoute un message de reussite
                                $this->addFlash('info', 'Votre inscription s\'est bien passée');
                                //on instancie un nouvel admin
                                $adm = new admin;
                                //on hash le mdp
                                $pwd = $hashedPwd->hashPassword($adm, $form['MDP']->getData());
                                //on définit les valeurs de Admin avec les champs du formulaire
                                $adm->setPseudo($form['pseudo']->getData())->setPassword($pwd)->setRoles(['ROLE_USER']);
                                // on définit le champs Admin de user avec notre objet admin
                                $user->setAdmin($adm);
                                //on envoie sur la bdd
                                $clientsRepository->add($user, true);
                                // on redirige sur la même page
                                return $this->redirectToRoute('app_signin');
                            } else {
                                // on ajoute un message flash avec le flag 'error'
                                $this->addFlash('error', 'Le mot de passe doit contenir au moins 10 caractères, 1minuscule, 1 majuscule, 1caractère spécial et 1 chiffre');
                            }
                        } else {
                            // on ajoute un message flash avec le flag 'error'
                            $this->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                        }
                    } else {
                        // on ajoute un message flash avec le flag 'error'
                        $this->addFlash('error', 'L\'adresse Mail est déjà utilisée');
                    }
                } else {
                    // on ajoute un message flash avec le flag 'error'
                    $this->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
                }
            } else {
                // on ajoute un message flash avec le flag 'error'
                $this->addFlash('error', 'Le pseudo est déjà utilisé');
            }
        }
        return $this->render('security/signin.html.twig', [
            'form' => $form->createView(),
            'categories' => $getCategories,
        ]);
    }

    static public function infoUtilisateurForm(UserPasswordHasherInterface $hashedPwd, ClientsRepository $clientsRepository, $form, AdminRepository $adminRepository, $user, SecurityController $secu){

        //si le pseudo est unique en bdd
        if (!$adminRepository->findBy(['pseudo' => $form['pseudo']->getData()])) {
            //si le telephone est unique en bdd
            if (!$clientsRepository->findBy(['telephone' => $form['telephone']->getData()])) {
                // si le mail est unique en bdd
                if (!$clientsRepository->findBy(['adresseMail' => $form['adresseMail']->getData()])) {
                    //si les 2 mot de passe rentrés sont égaux
                    if ($form['MDP']->getData() === $form['Confirmmdp']->getData()) {
                        // on prépare une regex
                        $exp = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
                        //si le mdp correspond à la regex
                        if (preg_match($exp, $form['MDP']->getData())) {
                            // on ajoute un message de reussite avec le flag 'info'
                            $secu->addFlash('info', 'Votre inscription s\'est bien passée');
                            //on instancie un nouvel admin
                            $adm = new admin;
                            //on hash le mdp
                            $pwd = $hashedPwd->hashPassword($adm, $form['MDP']->getData());
                            //on définit les valeurs de Admin avec les champs du formulaire
                            $adm->setPseudo($form['pseudo']->getData())->setPassword($pwd)->setRoles(['ROLE_USER']);
                            // on définit le champs Admin de user avec notre objet admin
                            $user->setAdmin($adm);

                            //on envoie sur la bdd
                            $clientsRepository->add($user, true);
                            // on redirige sur la même page
                            return SecurityController::redirectToRoute('app_signin');
                        } else {
                            // on ajoute un message flash avec le flag 'error'
                            $secu->addFlash('error', 'Le mot de passe doit contenir au moins 10 caractères, 1minuscule, 1 majuscule, 1caractère spécial et 1 chiffre');
                        }
                    } else {
                        // on ajoute un message flash avec le flag 'error'
                        $secu->addFlash('error', 'Vos 2 mots de passe ne correspondent pas');
                    }
                } else {
                    // on ajoute un message flash avec le flag 'error'
                    $secu->addFlash('error', 'L\'adresse Mail est déjà utilisée');
                }
            } else {
                // on ajoute un message flash avec le flag 'error'
                $secu->addFlash('error', 'Le numéro de téléphone est déjà utilisé');
            }
        } else {
            // on ajoute un message flash avec le flag 'error'
            $secu->addFlash('error', 'Le pseudo est déjà utilisé');
        }
    }
}
