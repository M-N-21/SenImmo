<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Image;
use App\Entity\Immeuble;
use App\Entity\Localite;
use App\Form\AppartementType;
use App\Form\ImmeubleType;
use App\Form\LocaliteType;
use App\Repository\AppartementRepository;
use App\Repository\ImmeubleRepository;
use App\Repository\LocaliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImmeubleController extends AbstractController
{
    #[Route('/immeuble', name: 'app_immeuble_index', methods: ['GET'])]
    public function index(ImmeubleRepository $immeubleRepository): Response
    {
        return $this->render('immeuble/index.html.twig', [
            'immeubles' => $immeubleRepository->findAll(),
        ]);
    }

    #[Route('/immeuble/new', name: 'app_immeuble_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocaliteRepository $rlocalite,ImmeubleRepository $immeubleRepository): Response
    {
        $immeuble = new Immeuble();
        $localite = new Localite();
        $form = $this->createForm(ImmeubleType::class, $immeuble);
        $formc = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);
        $formc->handleRequest($request);
        if ($formc->isSubmitted() && $formc->isValid()) {
            $rlocalite->save($localite, true);
            $this->addFlash('success', 'Localité ajouté avec succès');
            return $this->redirectToRoute('app_immeuble_new', [], Response::HTTP_SEE_OTHER);
        }
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
                $immeuble->addImage($img);
                $immeubleRepository->save($immeuble);
            }
            $immeubleRepository->save($immeuble, true);
            $this->addFlash('success', 'Immeuble ajouté avec succès');

            return $this->redirectToRoute('app_immeuble_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('immeuble/new.html.twig', [
            'immeuble' => $immeuble,
            'form' => $form,
            'formc' => $formc,
        ]);
    }

    #[Route('/show/immeuble/{id}', name: 'app_immeuble_show', methods: ['GET', 'POST'])]
    public function show(Immeuble $immeuble, AppartementRepository $rappart, Request $request): Response
    {
        $ap = new Appartement();
        $formc = $this->createForm(AppartementType::class, $ap);
        $formc->handleRequest($request);

        if ($formc->isSubmitted() && $formc->isValid()) {
            if ($ap->getEtage() > $immeuble->getNbreEtage()) {
                $this->addFlash('error', 'L\' etage à la quelle l\'appartement se trouve n\'existe pas');
            } else {
                $ap->setImmeuble($immeuble);
                $rappart->save($ap, true);
                $this->addFlash('success', "Appartement ajouté avec succès dans l'immeuble ". $immeuble->getNomImmeuble());
                return $this->redirect('/show/immeuble/'.$immeuble->getId());
            }

        }
        return $this->renderForm('immeuble/show.html.twig', [
            'immeuble' => $immeuble,
            'form' => $formc
        ]);
    }

    #[Route('/immeuble/{id}/edit', name: 'app_immeuble_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Immeuble $immeuble, ImmeubleRepository $immeubleRepository): Response
    {
        $form = $this->createForm(ImmeubleType::class, $immeuble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $immeubleRepository->save($immeuble, true);

            return $this->redirectToRoute('app_immeuble_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('immeuble/edit.html.twig', [
            'immeuble' => $immeuble,
            'form' => $form,
        ]);
    }

    #[Route('/immeuble/{id}', name: 'app_immeuble_delete', methods: ['POST'])]
    public function delete(Request $request, Immeuble $immeuble, ImmeubleRepository $immeubleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$immeuble->getId(), $request->request->get('_token'))) {
            $immeubleRepository->remove($immeuble, true);
        }

        return $this->redirectToRoute('app_immeuble_index', [], Response::HTTP_SEE_OTHER);
    }

}