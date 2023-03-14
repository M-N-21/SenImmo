<?php

namespace App\Controller;

use App\Entity\Localite;
use App\Form\LocaliteType;
use App\Repository\LocaliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/localite')]
class LocaliteController extends AbstractController
{
    #[Route('/', name: 'app_localite_index', methods: ['GET'])]
    public function index(LocaliteRepository $localiteRepository): Response
    {
        return $this->render('localite/index.html.twig', [
            'localites' => $localiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_localite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocaliteRepository $localiteRepository): Response
    {
        $localite = new Localite();
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localiteRepository->save($localite, true);

            return $this->redirectToRoute('app_localite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localite/new.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localite_show', methods: ['GET'])]
    public function show(Localite $localite): Response
    {
        return $this->render('localite/show.html.twig', [
            'localite' => $localite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localite $localite, LocaliteRepository $localiteRepository): Response
    {
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localiteRepository->save($localite, true);

            return $this->redirectToRoute('app_localite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localite/edit.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localite_delete', methods: ['POST'])]
    public function delete(Request $request, Localite $localite, LocaliteRepository $localiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localite->getId(), $request->request->get('_token'))) {
            $localiteRepository->remove($localite, true);
        }

        return $this->redirectToRoute('app_localite_index', [], Response::HTTP_SEE_OTHER);
    }
}
