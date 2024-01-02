<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Users;
use App\Form\AdminUserFormType;
use App\Repository\AddressRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;


#[Route('/admin')]
class AdminUserController extends AbstractController
{


    // Afficher la liste des utilisateurs --------------------------------------------------------------------------------

    // #[Route('/listUsers', name: 'app_listUsers')]
    // public function listUsers(Request $request, UsersRepository $usersrepository, Security $security): Response
    // {

    //     if (!$security->isGranted('ROLE_ADMIN')) {
    //         return $this->redirectToRoute('app_admin_login');
    //     }

    //     $trinom = $request->query->get('lastname', 'asc');
    //     $triprenom = $request->query->get('firstname', 'asc');
    //     $trimail = $request->query->get('email', 'asc');

    //     $query = $usersrepository->findAll(); // chercher tous les users

    //     if (empty($query)) {
    //         $users = null;
    //     } else {
    //         $users = $paginator->paginate(
    //             $query,
    //             $request->query->getInt('page', 1), // numéro de page
    //             5 // limite par page
    //         );
    //     }
    //     $countUsers = $usersrepository->countUsers();


    //     return $this->render('admin_user/index.html.twig', [
    //         'users' => $users,
    //         'countUsers' => $countUsers,
    //         'trinom' => $trinom,
    //         'triprenom' => $triprenom,
    //         'trimail' => $trimail,
    //     ]);
    // }


    // Afficher un user --------------------------------------------------------------------------------

    #[Route('/showUser/{id}', name: 'app_showUser')]
    public function showUser(Users $users, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        if ($users === null) {
            return $this->redirectToRoute('app_listUsers');
        }
        return $this->render('admin_user/show.html.twig', [
            'user' => $users,
        ]);
    }


    // Supprimer un user --------------------------------------------------------------------------------

    #[Route('/removeUser/{id}', name: 'app_removeUser')]
    public function removeUser(Users $users, UsersRepository $usersrepository, Request $request, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        if ($request->isMethod('POST')) {
            $usersrepository->remove($users);
            return $this->redirectToRoute('app_listUsers');
        }

        return $this->render('admin_user/remove.html.twig', [
            'user' => $users,
        ]);
    }



    // Modifier un user --------------------------------------------------------------------------------

    #[Route('/editUser/{id}', name: 'app_editUser')]
    public function editUser(Security $security, Users $user, Request $request, UsersRepository $usersrepository, AddressRepository $addressRepository, EntityManagerInterface $em): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        $message = '';

        // vérifier la méthod POST du formulaire 
        if ($request->getMethod() === "POST") {
            // récupération des valeurs du formulaire
            $lastname = $request->request->get("lastname");
            $firstname = $request->request->get("firstname");
            $email = $request->request->get('email');
            $oldaddress = $user->getAddress();
            $newaddress = $request->request->get('address');

            if ($oldaddress !== null) {
                $oldaddress->setAddress($newaddress);
            }
            else {
                // Create a new Address object and set it to the user
                $newAddressObject = new Address();
                $newAddressObject->setAddress($newaddress);
                $user->setAddress($newAddressObject);
                $em->persist($newAddressObject); // persist Address entity
            }

            // si les champs sont vides, renvoyer message d'erreur
            if ($lastname === "" || $firstname === "" || $email === "" || $newaddress === "") {
                $message = "Fields must not be empty";
            } 
            
            else {
                // création d'un nouvel utilisateur et utilisation des méthode de l'entité Users


                $user->setLastname($lastname);
                $user->setFirstname($firstname);
                $user->setEmail($email);
                /* $oldaddress->setAddress($newaddress); */
                $usersrepository->save($user);
                $message = "User has been modified.";
                return $this->redirectToRoute('app_listUsers');
            }
        }

        return $this->render('admin_user/edit.html.twig', [
            'user' => $user,
            'message' => $message,
        ]);
    }


    // Ajouter un nouvel utilisateur --------------------------------------------------------------------------------

    #[Route('/newuser', name: 'app_admin_new_user')]
    public function create(Security $security, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

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

    // rechercher utilisateur --------------------------------------------------------------------------------

    #[Route('/search', name: 'app_admin_user_search')]
    public function search(Security $security, Request $request, UsersRepository $usersrepository)
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

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
