<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Image;
use App\Form\AppartementType;
use App\Repository\AppartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppartementController extends AbstractController
{
    #[Route('/appartement', name: 'app_appartement_index', methods: ['GET'])]
    public function index(AppartementRepository $appartementRepository): Response
    {
        return $this->render('appartement/index.html.twig', [
            'appartements' => $appartementRepository->findAll(),
        ]);
    }

    #[Route('/appartement/new', name: 'app_appartement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AppartementRepository $appartementRepository): Response
    {
        $appartement = new Appartement();
        $form = $this->createForm(AppartementType::class, $appartement);
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
                $appartement->addImage($img);
                $appartementRepository->save($appartement);
            }
            $appartementRepository->save($appartement, true);

            return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/new.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    #[Route('/show/appartement/{id}', name: 'app_appartement_show', methods: ['GET'])]
    public function show(Appartement $appartement): Response
    {
        return $this->render('appartement/show.html.twig', [
            'appartement' => $appartement,
        ]);
    }

    #[Route('/appartement/{id}/edit', name: 'app_appartement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appartement $appartement, AppartementRepository $appartementRepository): Response
    {
        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appartementRepository->save($appartement, true);

            return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/edit.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    #[Route('/appartement/{id}', name: 'app_appartement_delete', methods: ['POST'])]
    public function delete(Request $request, Appartement $appartement, AppartementRepository $appartementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appartement->getId(), $request->request->get('_token'))) {
            $appartementRepository->remove($appartement, true);
        }

        return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
    }
}