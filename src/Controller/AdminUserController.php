<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, ObjectManager $manager, User $user){

        if(count($user->getBookings()) > 0){
            $this->addFlash("warning", "Vous ne pouvez pas supprimer cet utilisateur car il possède des réservations !");
        } else {
            $manager->remove($user);
            $manager->flush();
            $this->addFlash("success", "L'utilisateur a bien été supprimé !");
        }; 

        return $this->redirectToRoute("admin_users");
    }
}
