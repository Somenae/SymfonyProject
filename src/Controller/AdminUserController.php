<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Users;
use App\Form\AdminUserFormType;
use App\Form\AdminUserSearchFormType;
use App\Repository\AddressRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/admin')]
class AdminUserController extends AbstractController
{


    // Afficher la liste des utilisateurs
    #[Route('/listUsers', name: 'app_listUsers')]
    public function listUsers(Request $request, UsersRepository $usersrepository, PaginatorInterface $paginator): Response
    {
        $trinom = $request->query->get('lastname','asc');
        $triprenom = $request->query->get('firstname','asc');
        $trimail = $request->query->get('email','asc');

        $query = $usersrepository->findAll(); // chercher tous les users
        $users = $paginator->paginate(  
            $query,
            $request->query->getInt('page', 1), // numéro de page
            5 // limite par page
        );
    
        $count = $usersrepository->countUsers();

        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
            'count' => $count,
            'trinom' => $trinom,
            'triprenom' => $triprenom,
            'trimail' => $trimail,
        ]);
    }



    // Afficher un user
    #[Route('/showUser/{id}', name: 'app_showUser')]
    public function showUser(Users $users): Response
    {
        if ($users === null) {
            return $this->redirectToRoute('app_listUsers');
        }
        return $this->render('admin_user/show.html.twig', [
            'user' => $users,
        ]);
    }


    // Supprimer un client
    #[Route('/removeUser/{id}', name: 'app_removeUser')]
    public function removeUser(Users $users, UsersRepository $usersrepository, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $usersrepository->remove($users);
            return $this->redirectToRoute('app_listUsers');
        }

        return $this->render('admin_user/remove.html.twig', [
            'user' => $users,
        ]);
    }



    // Modifier un user 
    #[Route('/editUser/{id}', name: 'app_editUser')]
    public function editUser(Users $user, Request $request, UsersRepository $usersrepository, AddressRepository $addressRepository): Response
    {

        $message = '';

        // vérifier la méthod POST du formulaire 
        if ($request->getMethod() === "POST") {
            // récupération des valeurs du formulaire
            $lastname = $request->request->get("lastname");
            $firstname = $request->request->get("firstname");
            $email = $request->request->get('email');
            $oldaddress = $user->getAddress();
            $newaddress = $request->request->get('address');

            // si les champs sont vides, renvoyer message d'erreur
            if ($lastname === "" || $firstname === "" || $email === "" || $newaddress === "") {
                $message = "Fields must not be empty";
            } else {
                // création d'un nouvel utilisateur et utilisation des méthode de l'entité Users

                
                $user->setLastname($lastname);
                $user->setFirstname($firstname);
                $user->setEmail($email);
                $oldaddress->setAddress($newaddress);
                $usersrepository->save($user);
                $message = "User has been modified.";
                return $this->redirectToRoute('app_listUsers');
            }
        }

        return $this->render('admin_user/edit.html.twig', [
            'user' => $user,
        ]);
    }




    // Ajouter un nouvel utilisateur
    #[Route('/newuser', name: 'app_admin_new_user')]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = new Users();
        $form = $this->createForm(AdminUserFormType::class, $user);
        $form->handleRequest($request);
        $message = '';
        $plainTextPassword = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->get('address')->getData();
            $addressString = $form->get('address')->getData();
            $address = new Address();
            $address->setAddress($addressString);
            $user->setAddress($address);

            $plainTextPassword = $_POST['admin_user_form']['password']['first'];
            $hashedPassword = $hasher->hashPassword(
                $user,
                $plainTextPassword
            );
            $user->setPassword($hashedPassword);

            $em->persist($address);
            //dd($address);
            $em->flush();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_listUsers');
        }

        return $this->render('admin_user/adduser.html.twig', [
            'form' => $form,
            'message' => $message,
        ]);
    }

    // rechercher utilisateur
    #[Route('/search', name: 'app_admin_user_search')]
    public function search(Request $request, UsersRepository $usersrepository)
{
    $request = $request->query->get('search');
    $result = [];
    $count = 0;

    if ($request) {
        $result = $usersrepository->search($request);
        $count = $usersrepository->countSearchResults($request);
    }


    return $this->render('admin_user/search.html.twig', [
        'result' => $result,
        'count' => $count,
    ]);
  
}
    
}
