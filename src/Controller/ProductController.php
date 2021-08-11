<?php
// src/Controller/ProductController.php
namespace App\Controller;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProductController extends AbstractController
{
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products =$entityManager->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
        
    }

    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('code', TextType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('brand', TextType::class)
            //->add('category', IntegerType::class)
            ->add('category', EntityType::class,[
                'class' => 'App\Entity\Category'
            ])
            ->add('brand', TextType::class)
            ->add('price', MoneyType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Product'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $data->setCreatedAt(new \DateTimeImmutable());
            $data->setUpdateAt(new \DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();

            $em->persist($data);
            $em->flush();
             $this->addFlash('success', 'Product Created!');
            return $this->redirectToRoute('app_product');

        }
        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Product $product, Request $request): Response
    {   
        $form = $this->createFormBuilder($product)
            ->add('code', TextType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('brand', TextType::class)
            //->add('category', IntegerType::class)
            ->add('category', EntityType::class,[
                'class' => 'App\Entity\Category'
            ])
            ->add('brand', TextType::class)
            ->add('price', MoneyType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Product'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Product Update!');
            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/new.html.twig', [
            'form' => $form->createView()
        ]);
        
        
    }



    public function show(Product $product): Response
    {   

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
        
        
    }

    public function delete(Product $product): Response
    {
        if (!$product) {
            throw $this->createNotFoundException('No product found');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        $this->addFlash('success', 'Product Deleted!');
        return $this->redirectToRoute('app_product');
    }
    
}
