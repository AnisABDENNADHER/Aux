<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    /**
     * @Route("/thread", name="thread")
     */
    public function index(): Response
    {
        return $this->render('thread/index.html.twig', [
            'controller_name' => 'ThreadController',
        ]);
    }

    /**
     * @Route("/addThread", name="add_thread")
     * @param Request $request
     * @return Response
     */
    public function addThread(Request $request): Response
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadType::class, $thread);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($thread);
            $em->flush();

            $file2 = $form->get('Image')->getData();
            //md5(uniqid()) . '.' . $file2->guessExtension(); //Crypter le nom de l'image
            $fileName = $file2->getClientOriginalName();
            $aux = $file2->guessExtension();

            if ($aux == "png" || $aux == "jpeg") {

                try {
                    $file2->move(
                        $this->getParameter('Images_directory'),
                        $fileName);
                        $entityManager = $this->getDoctrine()->getManager();
                        $thread->setImage($fileName);
                        $entityManager->persist($thread);
                        $entityManager->flush();
                    $this->addFlash('success', 'Réclamation ajoutée avec succès !');
                    return $this->redirectToRoute('add_thread');

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            else {
                echo 'Le fichier doit être une Image !';
        }

        }
        return $this->render('thread/ajout.html.twig', [
            'form' => $form->createView() ]);
    }

    /**
     * @Route("/Affiche", name="affichThread")
     * @param ThreadRepository $repository
     * @return Response
     */
    public function show(ThreadRepository $repository): Response
    {
        $thread = $repository->findAll();
        return $this->render('thread/affiche.html.twig', ['Thread' => $thread]);

    }

    /**
     * @Route("/Affichethread/ {id}", name="onClick")
     * @param $id
     * @param ThreadRepository $repository
     * @return Response
     */
    public function onClick($id, ThreadRepository $repository): Response
    {

        $thread = $repository->findOneBy(['id' => $id]);
        return $this->render('thread/affichethread.html.twig', ['ThreadOne' => $thread]);

    }
}