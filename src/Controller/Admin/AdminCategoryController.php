<?php

namespace App\Controller\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories")
 */
class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category")
     */
    public function index(): Response
    {   
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/new", name="admin_category_new")
    */
    public function new(Request $request){
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $category->setCreatedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $category->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categoria Salva com sucesso!');
            return $this->redirectToRoute('admin_category');
            
        }

        return $this->render('admin/categories/new.html.twig', [
            'armando' => $form->createView()
        ]);

    }

    /**
     * @Route("/update/{id}", name="admin_category_update")
    */
    public function update(Request $request, Category $id){
        
        $form = $this->createForm(CategoryType::class, $id);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $category->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categoria Atualizado com sucesso!');
            return $this->redirectToRoute('admin_category');
            
        }

        return $this->render('admin/categories/update.html.twig', [
            'armando' => $form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="admin_category_delete")
    */
    public function delete(Category $category){


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'Categoria REMOVIDO com sucesso!');
        return $this->redirectToRoute('admin_category');
    }

}
