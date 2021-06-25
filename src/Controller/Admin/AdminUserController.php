<?php

namespace App\Controller\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/users")
 */
class AdminUserController extends AbstractController
{
     /**
     * @Route("/", name="admin_users")
     */
    public function index(): Response
    {   
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new")
    */
    public function new(Request $request){
        $user = $this->createForm(UserType::class);
        $user->handleRequest($request);

        if($user->isSubmitted() && $user->isValid()){
            $post = $user->getData();

            $post->setCreatedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $post->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            
            // $user = $this->getDoctrine()->getRepository(User::class)->find(1);

            // $post->setAuthor($user);            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Usuário Salvo com sucesso!');
            return $this->redirectToRoute('admin_users');
            
        }

        return $this->render('admin/users/new.html.twig', [
            'armando' => $user->createView()
        ]);

    }

    /**
     * @Route("/update/{id}", name="admin_user_update")
    */
    public function update(Request $request, User $id){
        
        $user = $this->createForm(UserType::class, $id);
        $user->handleRequest($request);

        if($user->isSubmitted() && $user->isValid()){
            $post = $user->getData();

            $post->setUpdateAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($post);
            $entityManager->flush();

            $this->addFlash('success', 'Usuário Atualizado com sucesso!');
            return $this->redirectToRoute('admin_users');
            
        }

        return $this->render('admin/users/update.html.twig', [
            'armando' => $user->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete")
    */
    public function delete(User $post){


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post REMOVIDO com sucesso!');
        return $this->redirectToRoute('admin_users');
    }
}
