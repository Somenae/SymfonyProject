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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;


#[Route('admin/')]
class AdminProductController extends AbstractController
{

/* index */
    #[Route('product', name: 'app_admin_product')]
    public function index(ProductRepository $ProductRepository): Response
    {
        $products = $ProductRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,  
        ]);
    }

/* Creer un produit */
    #[Route('createproduct', name:'app_admin_create_product')]
    public function createArticle(
        Request $request, 
        EntityManagerInterface $em,
        ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($product);
            $em->flush(); 
            return $this->redirectToRoute('app_admin_product');
        } 
       
        return $this->render('product/create.html.twig',[
            'title' => 'Création d\'un nouveau produit',
            'form' => $form->createView(),
        ]);
    }

/* Modifier un produit */
    #[Route('updateproduct/{id}', name: 'app_admin_update_product')]
    public function update(
        Request $request, 
        ?Product $product,
        EntityManagerInterface $em,
        )
    {
      
        if ($product === NULL) {
            return $this->redirectToRoute('app_admin_product');
        }   

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('product/update.html.twig', [
            'form' => $form,
            'product' => $product,
            
        ]);
    }

/* Afficher la liste des produits */
    #[Route('list', name: 'app_list_product')]
    public function list(
        ProductRepository $ProductRepository,
        ?Product $product
        ): Response
    {
        if ($product === null) {
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('product/list.html.twig', [
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
      
    ): Response 
    {
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
        Product $product
        ): Response
    {   
        return $this->render('product/show.html.twig', [
            'title' => 'Détails du Produit',
            'product' => $product,
        ]);
    }

/* Inserer une image */
    #[Route('addimage/{id}', name : 'app_admin_add_image')]
public function addImage(
    Product $product,
    Request $request,
    EntityManagerInterface $em,
    SluggerInterface $slugger
): Response 
{
    $message ='';
    $form = $this->createForm(ImageFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) 
    {  
        $image = $form->get('image')->getData();
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $product->getName().$product->getId().'.'.$image->guessExtension();
       
        try {
            $image->move(
                $this->getParameter("product_directory"),
                $newFilename
            );
            }
        catch (FileException $e) {
        
    } 
    
        $product ->setImage($newFilename);
        $em->persist($product);
        $em->flush();

        return $this->render('product/addimage.html.twig', array(
            'title' => 'Inserer ou modifier l\'image de l\'album',
            'form' => $form,
            'message' => 'Votre image a bien été inserer ou modifier'
        ));
}
 
        else {


            return $this->render('product/addimage.html.twig', array(
                'title' => 'Inserer ou Modifier l\'image de l\'album',
                'form' => $form,
                'message' => ''
            ));
            }
        }

    }