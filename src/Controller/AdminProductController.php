<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ImageFormType;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;
use Symfony\Component\Security\Core\Security;




#[Route('admin/')]
class AdminProductController extends AbstractController
{

    /* index */
    #[Route('product', name: 'app_admin_product')]
    public function index(ProductRepository $ProductRepository, Security $security, Request $request, CategoryRepository $categoryRepository): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        $products = $ProductRepository->findAll();
        $categories = $categoryRepository->findAll();


        return $this->render('admin_product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,

        ]);
    }

    /* Rechercher un produit */

    #[Route('searchProduct', name: 'app_admin_product_search')]
    public function search(Security $security, Request $request, ProductRepository $productRepository)
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        $request = $request->query->get('search');
        $result = [];
        $count = 0;

        if ($request) {
            $result = $productRepository->search($request);
            $count = $productRepository->countSearchResults($request);
        }


        return $this->render('admin_product/search.html.twig', [
            'result' => $result,
            'count' => $count,
        ]);
    }


    /* Creer un produit */
    #[Route('createproduct', name: 'app_admin_create_product')]
    public function createArticle(
        Request $request,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }


        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        $imageForm = $this->createForm(ImageFormType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/create.html.twig', [
            'title' => 'Création d\'un nouveau produit',
            'form' => $form->createView(),
            'product' => $product,
            'imageForm' => $imageForm->createView(),
        ]);
    }

    /* Modifier un produit */
    #[Route('updateproduct/{id}', name: 'app_admin_update_product')]
    public function update(
        Request $request,
        ?Product $product,
        EntityManagerInterface $em,
        Security $security
    ) {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        if ($product === NULL) {
            return $this->redirectToRoute('app_admin_product');
        }

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/update.html.twig', [
            'form' => $form,
            'product' => $product,

        ]);
    }

    /* Afficher la liste des produits */
    #[Route('list', name: 'app_list_product')]
    public function list(
        ProductRepository $ProductRepository,
        ?Product $product, 
        Security $security
    ): Response {



        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }


        if ($product === null) {
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/list.html.twig', [
            'title' => 'Fiche d\'un produit',
            'product' => $product,
        ]);
    }

    /* Supprimer un produit */
    #[Route('deleteproduct/{id}', name: 'app_admin_delete_product')]
    public function deleteProduct(
        ?Product $product,
        EntityManagerInterface $em,
        Request $request,
        Security $security

    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }


        if ($product === null) {
            throw $this->createNotFoundException('Produit non trouvée');
        }

        $token = $request->request->get('token');

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('app_admin_product');
    }

    /* Voir un produit */
    #[Route('showproduct/{id}', name: 'app_admin_show_product')]
    public function showProduct(
        Product $product,
        Security $security
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }


        return $this->render('admin_product/show.html.twig', [
            'title' => 'Détails du Produit',
            'product' => $product,
        ]);
    }
 


    /* Inserer une image */
    #[Route('addimage/{id}', name: 'app_admin_add_image')]
    public function addImage(
        Product $product,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        Security $security
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }


        $message = '';
        $form = $this->createForm(ImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $name = $product->getName();
            $id = $product->getId();
            $extension = $image->guessExtension();
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $name);
            $newFilename = $cleanName . $id . '.' . $extension;

/*             $newFilename = $product->getName() . $product->getId() . '.' . $image->guessExtension(); */

            try {
                $image->move(
                    $this->getParameter("product_directory"),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $product->setImage($newFilename);
            $em->persist($product);
            $em->flush();

            return $this->render('admin_product/addimage.html.twig', array(
                'title' => 'Inserer ou modifier l\'image de l\'album',
                'form' => $form,
                'message' => 'Votre image a bien été ajoutée ou modifiée.'
            ));
        } else {


            return $this->render('admin_product/addimage.html.twig', array(
                'title' => 'Inserer ou Modifier l\'image de l\'album',
                'form' => $form,
                'message' => ''
            ));
        }
    }
}
