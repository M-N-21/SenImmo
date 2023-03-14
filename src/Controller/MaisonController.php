<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Maison;
use App\Form\MaisonType;
use App\Repository\MaisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaisonController extends AbstractController
{
    #[Route('/maison', name: 'app_maison_index', methods: ['GET'])]
    public function index(MaisonRepository $maisonRepository): Response
    {
        return $this->render('maison/index.html.twig', [
            'maisons' => $maisonRepository->findAll(),
        ]);
    }

    #[Route('/maison/new', name: 'app_maison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaisonRepository $maisonRepository): Response
    {
        $maison = new Maison();
        $form = $this->createForm(MaisonType::class, $maison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            //On boucle sur les images
            foreach ($images as $i) {
                //On genere un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $i->guessExtension();
                $i->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                //on stock l'image dans la BD (son nom)
                $img = new Image();
                $img->setName($fichier);
                $maison->addImage($img);
                $maisonRepository->save($maison);
            }
            $maisonRepository->save($maison, true);

            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maison/new.html.twig', [
            'maison' => $maison,
            'form' => $form,
        ]);
    }

    #[Route('/show/maison/{id}', name: 'app_maison_show', methods: ['GET'])]
    public function show(Maison $maison): Response
    {
        return $this->render('maison/show.html.twig', [
            'maison' => $maison,
        ]);
    }

    #[Route('/maison/{id}/edit', name: 'app_maison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        $form = $this->createForm(MaisonType::class, $maison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maisonRepository->save($maison, true);

            return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maison/edit.html.twig', [
            'maison' => $maison,
            'form' => $form,
        ]);
    }

    #[Route('/maison/{id}', name: 'app_maison_delete', methods: ['POST'])]
    public function delete(Request $request, Maison $maison, MaisonRepository $maisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maison->getId(), $request->request->get('_token'))) {
            $maisonRepository->remove($maison, true);
        }

        return $this->redirectToRoute('app_maison_index', [], Response::HTTP_SEE_OTHER);
    }
}