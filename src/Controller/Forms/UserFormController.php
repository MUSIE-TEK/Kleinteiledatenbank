<?php

namespace App\Controller\Forms;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\RepositoryHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
//Hier kommen die Anfragen an, d.h. Routen sind darin defininiert und dadurch wird die dazugehörige Funktion aufgerufen.
class UserFormController extends AbstractController
{

    private RepositoryHelper $repositoryHelper;


    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(RepositoryHelper $repositoryHelper, UserPasswordHasherInterface $passwordHasher)
    {
        $this->repositoryHelper = $repositoryHelper;
        $this->passwordHasher = $passwordHasher;
    }

    //hier werden parametriesiete URLs generiert. A
    #[Route('/admin/user', name: 'app_user_list')]
    public function userList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $this->repositoryHelper->getUserRepository();
        $users = $userRepository->findAll();

        return $this->render('forms/users/list.html.twig', [
            'users' => $users,
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/admin/user/edit/{userId}', name: 'app_user_edit')]
    public function userEdit($userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        // https://symfony.com/doc/current/forms.html
        $userRepository = $this->repositoryHelper->getUserRepository(); // get the userrepository
        $user = $userRepository->find($userId); // find the db entry with id $userId
        $user->eraseCredentials(); // remove credentials

        $form = $this->createForm(UserType::class, $user); // creates a view with the type UserType and the data from $user

        $form->handleRequest($request); // checks the request (get / post)
        if ($form->isSubmitted() && $form->isValid()) { // checks if the form was submitted (post) or get request
            /** @var User $user */
            $user = $form->getData();
            $userRepository->updateRoles($user);
            if ($user->getPlainPassword() !== null) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
                $entityManager->persist($user);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_user_list'); // redirect the user to the user list
        }

        // render the view with the created form
        return $this->render('forms/users/edit.html.twig', [
            'userEditForm' => $form->createView(),
        ]);
    }
}
