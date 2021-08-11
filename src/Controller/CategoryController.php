<?php
// src/Controller/CategoryController.php
namespace App\Controller;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class CategoryController extends AbstractController
{
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorys =$entityManager->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categorys' => $categorys,
        ]);
        
    }

    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('active', ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false
                    ]
                ])
            ->add('save', SubmitType::class, ['label' => 'Create Category'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $data->setCreatedAt(new \DateTimeImmutable());
            $data->setupdatedAt(new \DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();

            $em->persist($data);
            $em->flush();
             $this->addFlash('success', 'Category Created!');
            return $this->redirectToRoute('app_category');

        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Category $category, Request $request): Response
    {   
        $form = $this->createFormBuilder($category)
            
            ->add('name', TextType::class)
            ->add('active', ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false
                    ]
                ])
            ->add('save', SubmitType::class, ['label' => 'Update Category'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Category Update!');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
        
        
    }



    public function show(Category $category): Response
    {   

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
        
        
    }

    public function delete(Category $category): Response
    {
        if (!$category) {
            throw $this->createNotFoundException('No category found');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category Deleted!');
        return $this->redirectToRoute('app_category');
    }
    
}
