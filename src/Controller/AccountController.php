<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error != null,
            'username' => $username
        ]);
      
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return response
     */
    public function logout(){
        //rien
    }

    /**
     * Permet de f'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */

     public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

         $user = new User();

         $form = $this->createForm(RegistrationType::class, $user);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
             $hash = $encoder->encodePassword($user, $user->getHash());
             $user->setHash($hash);
             $manager->persist($user);
             $manager->flush();

             $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez maintenant vous connecter !');

             return $this->redirectToRoute('account_login');
         }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
     }

     /**
      * Permet d'afficher et de traiter le formulaire de modification de profil
      *
      * @Route("/account/profile", name="account_profile")
      * @IsGranted("ROLE_USER")
      *
      * @return Response
      */

     public function profile(Request $request, ObjectManager $manager){

        $user = $this->getUser();
         
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Les données du profil ont été enregistrées avec succès !");
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
     }

    /**
     * Permet d'afficher et de traiter le formulaire de modif du mot de passe
     * 
     * @Route("/account/updatePassword", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $updatePwd = new PasswordUpdate();
        //le user est deja connecté, plus qu'à récupérer ses données
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $updatePwd);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //verification  du oldPassword par rapport à c eque j'ai dans la BDD
            if(!password_verify($updatePwd->getOldPassword(), $user->getHash())){
                //symfony me permet d'acceder au champ de mon formulaire
                $form->get('oldPassword')->addError(new FormError("le mot de passe saisi est erroné !"));
            } else {
                $newPassword = $updatePwd->getNewPassword();
                $hashPwd = $encoder->encodePassword($user, $newPassword);
                echo $hashPwd;
                $user->setHash($hashPwd);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Le mot de passe a bien été modifié !');

                return $this->redirectToRoute('homepage');
            }          
        }

       return $this->render('account/password.html.twig', [
           'form' => $form->createView()
       ]);
    }
    /**
     * Permet d'afficher le profil de l'utilisateur
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */

    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }       

}
