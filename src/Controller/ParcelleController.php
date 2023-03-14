<?php

namespace App\Controller;

use App\Entity\Parcelle;
use App\Form\ParcelleType;
use App\Repository\ParcelleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcelleController extends AbstractController
{
    #[Route('/parcelle', name: 'app_parcelle_index', methods: ['GET'])]
    public function index(ParcelleRepository $parcelleRepository): Response
    {
        return $this->render('parcelle/index.html.twig', [
            'parcelles' => $parcelleRepository->findAll(),
        ]);
    }

    #[Route('/parcelle/new', name: 'app_parcelle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParcelleRepository $parcelleRepository): Response
    {
        $parcelle = new Parcelle();
        $form = $this->createForm(ParcelleType::class, $parcelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcelleRepository->save($parcelle, true);

            return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parcelle/new.html.twig', [
            'parcelle' => $parcelle,
            'form' => $form,
        ]);
    }

    #[Route('/show/parcelle/{id}', name: 'app_parcelle_show', methods: ['GET'])]
    public function show(Parcelle $parcelle): Response
    {
        return $this->render('parcelle/show.html.twig', [
            'parcelle' => $parcelle,
        ]);
    }

    #[Route('/parcelle/{id}/edit', name: 'app_parcelle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parcelle $parcelle, ParcelleRepository $parcelleRepository): Response
    {
        $form = $this->createForm(ParcelleType::class, $parcelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcelleRepository->save($parcelle, true);

            return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parcelle/edit.html.twig', [
            'parcelle' => $parcelle,
            'form' => $form,
        ]);
    }

    #[Route('/parcelle/{id}', name: 'app_parcelle_delete', methods: ['POST'])]
    public function delete(Request $request, Parcelle $parcelle, ParcelleRepository $parcelleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parcelle->getId(), $request->request->get('_token'))) {
            $parcelleRepository->remove($parcelle, true);
        }

        return $this->redirectToRoute('app_parcelle_index', [], Response::HTTP_SEE_OTHER);
    }
}