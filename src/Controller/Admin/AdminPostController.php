<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/posts")
 */
class AdminPostController extends AbstractController
{
    /**
     * @Route("/", name="admin_post")
     */
    public function index(): Response
    {   
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        return $this->render('admin/posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/new", name="admin_post_new")
    */
    public function new(Request $request){
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();

            $post->setCratedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $post->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post Salvo com sucesso!');
            return $this->redirectToRoute('admin_post');
            
        }

        return $this->render('admin/posts/new.html.twig', [
            'armando' => $form->createView()
        ]);

    }

    /**
     * @Route("/update/{id}", name="admin_post_update")
    */
    public function update(Request $request, Post $id){
        
        $form = $this->createForm(PostType::class, $id);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();

            $post->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post Atualizado com sucesso!');
            return $this->redirectToRoute('admin_post');
            
        }

        return $this->render('admin/posts/update.html.twig', [
            'armando' => $form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="admin_post_delete")
    */
    public function delete(Post $post){


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post REMOVIDO com sucesso!');
        return $this->redirectToRoute('admin_post');
    }

}
