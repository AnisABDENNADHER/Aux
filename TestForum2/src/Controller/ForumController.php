<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('Forum/index.html.twig');
    }

    /**
     * @Route("/add", name="add_article")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadType::class, $thread);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on commence par récupérer le champ "picture" du formulaire
            $picture = $form->get("picture")->getData();
            if ($picture) { // on vérifie si l'utilisateur a renseigner une image
                // on crée un nouveau nom que nous allons utiliser pour l'image
                $fileName =  uniqid(). '.' .$picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                // le champ "picture" va contenir le nom du fichier sur le disque dure
                $thread->setPicture($fileName);



            // on récupère l'entity manager qui va nous permettre d'interagir avec la BDD
            $em = $this->getDoctrine()->getManager();

            // on confie l'objet $article à l''entity manager (on le persiste)
            $em->persist($thread);

            // on exécute la requête en base de données
            $em->flush();

            return new Response("L'article a bien été enregitrer.");
        }
            }
        return $this->render('forum/add.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/edit/{slug}", name="edit_article")
     * @param Thread $thread
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Thread $thread, Request $request) {
        $form = $this->createForm(ThreadType::class, $thread);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // on commence par récupérer le champ "picture" du formulaire
            $picture = $form->get("picture")->getData();
            if ($picture) { // on vérifie si l'utilisateur a renseigner une image
                // on vérifie si l'article avait déjà une image
                if ($thread->getPicture() !== null) {
                    // on supprime l'image sur le serveur
                    unlink($this->getParameter('images_directory'). '/' .$thread->getPicture());
                }

                // on crée un nouveau nom que nous allons utiliser pour l'image
                $fileName =  uniqid(). '.' .$picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                // le champ "picture" va contenir le nom du fichier sur le disque dure
                $thread->setPicture($fileName);
            }

            $thread->setUpdatedAt(new \DateTime);

            // on récupère l'entity manager qui va nous permettre d'interagir avec la BDD
            $em = $this->getDoctrine()->getManager();

            // cette fois ci on ne persiste plus l'article,
            // l'entity manager le connait déjà parce qu'il est aller le chercher en BDD

            // on exécute la requête en base de données
            $em->flush();

            return $this->redirectToRoute(
                'edit_article',
                ['slug' => $thread->getSlug()]
            );
        }

        return $this->render(
            'forum/edit.html.twig',
            ['article' => $thread, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/publish/{slug}", name="publish_article")
     * @param Thread $thread
     * @return RedirectResponse
     */
    public function publish(Thread $thread)
    {
        if ($thread->getPicture() !== null && $thread->getPicture() !== ''
            && $thread->getContent() !== null && $thread->getContent() !== ''
            && $thread->getCategories() !== null
            && $thread->getTitle() !== null && $thread->getTitle() !== ''
        ) {
            $thread->setPublishedAt(new \DateTime);
            $thread->setIsPublished(true);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // on redirige vers la page d'affichage d'un article si tout est OK
            return $this->redirectToRoute(
                'show_article',
                ['slug' => $thread->getSlug()]
            );
        }

        return $this->redirectToRoute(
            'edit_article',
            ['slug' => $thread->getSlug()]
        );
    }

    /**
     * @Route("/show/{slug}", name="show_article")
     * @param Thread $thread
     * @return Response
     */
    public function show(Thread $thread)
    {
        return $this->render('forum/show.html.twig', ['article' => $thread]);
    }

    /**
     * @Route("/delete/{slug}", name="delete_thread")
     * @param string $slug
     * @return Response
     */
    public function delete(string $slug)
    {
        return new Response("<h1>Contrôleur pour supprimer l'article: $slug</h1>");
    }
}