<?php

namespace App\Controller;

use App\Entity\DetailReglement;
use App\Form\DetailReglementType;
use App\Repository\DetailReglementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/detail/reglement')]
class DetailReglementController extends AbstractController
{
    #[Route('/', name: 'app_detail_reglement_index', methods: ['GET'])]
    public function index(DetailReglementRepository $detailReglementRepository): Response
    {
        return $this->render('detail_reglement/index.html.twig', [
            'detail_reglements' => $detailReglementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_detail_reglement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DetailReglementRepository $detailReglementRepository): Response
    {
        $detailReglement = new DetailReglement();
        $form = $this->createForm(DetailReglementType::class, $detailReglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailReglementRepository->save($detailReglement, true);

            return $this->redirectToRoute('app_detail_reglement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detail_reglement/new.html.twig', [
            'detail_reglement' => $detailReglement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_detail_reglement_show', methods: ['GET'])]
    public function show(DetailReglement $detailReglement): Response
    {
        return $this->render('detail_reglement/show.html.twig', [
            'detail_reglement' => $detailReglement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_detail_reglement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DetailReglement $detailReglement, DetailReglementRepository $detailReglementRepository): Response
    {
        $form = $this->createForm(DetailReglementType::class, $detailReglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailReglementRepository->save($detailReglement, true);

            return $this->redirectToRoute('app_detail_reglement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detail_reglement/edit.html.twig', [
            'detail_reglement' => $detailReglement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_detail_reglement_delete', methods: ['POST'])]
    public function delete(Request $request, DetailReglement $detailReglement, DetailReglementRepository $detailReglementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailReglement->getId(), $request->request->get('_token'))) {
            $detailReglementRepository->remove($detailReglement, true);
        }

        return $this->redirectToRoute('app_detail_reglement_index', [], Response::HTTP_SEE_OTHER);
    }
}
