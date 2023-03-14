<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\Offre;
use App\Form\ClientType;
use App\Form\ContratAppartementType;
use App\Form\ContratMaisonType;
use App\Form\ContratParcelleType;
use App\Form\ContratType;
use App\Form\ContratVenteAppartementType;
use App\Form\ContratVenteMaisonType;
use App\Form\ContratVenteParcelleType;
use App\Repository\AppartementRepository;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Repository\MaisonRepository;
use App\Repository\OffreRepository;
use App\Repository\ParcelleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    private function numcontrat(ContratRepository $rcontrat)
    {
        $contrats = $rcontrat->findAll();
        if (!$contrats) return $numcontrat = "0001";
        else {
            foreach ($contrats as $c) {
                $num = (int)$c->getNumero();
            }
            $num += 1;
            settype($num, "string");
            // dd($num);
            if (strlen($num) == 1)
                $num = "0000" . $num;
            else if (strlen($num) == 2)
                $num = "000" . $num;
            else if (strlen($num) == 3)
                $num = "00" . $num;
            else if (strlen($num) == 4)
                $num = "0" . $num;
            else
                $num =  $num;
        }
        // dd($num);
        return $num;
    }

    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }
    #[Route('/location', name: 'app_contratlocation_index', methods: ['GET'])]
    public function location(ContratRepository $contratRepository, MaisonRepository $rmaison): Response
    {
        $maisons = $rmaison->findAll();
        $contrats = null;
        $contratall = $contratRepository->findAll();
        foreach ($maisons as $m) {
            if ($m->getOffre()->getTypeOffre() == "location" && !$m->isDisponibilite()) {
                foreach ($contratall as $c) {
                    if ($c->getMaison() == $m) {
                        $contrats[] = $c;
                    }
                }
            }
        }
        // dd($contrats);
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contrats,
            'vente' => false,
        ]);
    }
    #[Route('/vente', name: 'app_contratvente_index', methods: ['GET'])]
    public function vente(ContratRepository $contratRepository, MaisonRepository $rmaison): Response
    {
        $maisons = $rmaison->findAll();
        $contratall = $contratRepository->findAll();
        foreach ($maisons as $m) {
            if ($m->getOffre()->getTypeOffre() == "vente" && !$m->isDisponibilite()) {
                foreach ($contratall as $c) {
                    if ($c->getMaison() == $m) {
                        $contrats[] = $c;
                    }
                }
            }
        }
        // dd($contrats);
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contrats,
            'vente' => true,
        ]);
    }


    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContratRepository $contratRepository, MaisonRepository $rmaison,): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $maisons = $rmaison->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($maisons as $m) {
                if ($m == $contrat->getMaison()) {
                    if ($m->getOffre()->getTypeOffre() == 'vente') {
                        if (!$m->isDisponibilite()) {
                            $this->addFlash('error', 'Cette maison a déja été vendu!');
                            break;
                        } else {
                            // if ($contrat->getDateDebut() <= $contrat->getDateFin()) {
                            //     if () {
                            //         # code...
                            //     }
                            // }
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rmaison->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
                        }
                    } else {
                        $trouve = false;
                        foreach ($contrats as $c) {
                            if ($c->getMaison() == $m) {
                                if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                    $this->addFlash('error', "Cette maison est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                    $trouve = true;
                                    break;
                                }
                            }
                        }
                        if (!$trouve) {
                            $contrat->setDateContrat(new \DateTime());
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rmaison->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/newa', name: 'app_contratappartement_new', methods: ['GET', 'POST'])]
    public function newa(Request $request, ContratRepository $contratRepository, ClientRepository $rclient, AppartementRepository $rappartement,): Response
    {
        $contrat = new Contrat();
        $client = new Client();
        $formc = $this->createForm(ClientType::class, $client);
        $contrat->setNumero($this->numcontrat($contratRepository));
        $form = $this->createForm(ContratAppartementType::class, $contrat);
        $form->handleRequest($request);
        $formc->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $appartements = $rappartement->findAll();
        if ($formc->isSubmitted() && $formc->isValid()) {
            $rclient->save($client, true);
            $this->addFlash('success', 'Client enregistré avec succès!');
            return $this->redirectToRoute('app_contratmaison_new');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($contrat->getDateDebut() >= $contrat->getDateFin()) {
                $this->addFlash('error', 'La date de debut du contrat ne peut pas etre postérieur ou egal à la date de fin du contrat');
            } else {
                if ($contrat->getDateDebut()->format('Y') == $contrat->getDateFin()->format('Y')) {
                    // dd($contrat->getDateDebut()->format('Y'));
                    if (((int)$contrat->getDateFin()->format('m') - (int)$contrat->getDateDebut()->format('m')) < 3) {
                        // dd($contrat->getDateDebut()->format('m'));
                        $this->addFlash('error', 'Le contrat de location doit etre au minimum de 3 mois!');
                    } else {
                        foreach ($appartements as $m) {
                            if ($m == $contrat->getParcelle()) {
                                if ($m->getOffre()->getTypeOffre() == 'vente') {
                                    if (!$m->isDisponibilite()) {
                                        $this->addFlash('error', 'Cette appartement a déja été vendu!');
                                        break;
                                    } else {
                                        $contratRepository->save($contrat, true);
                                        $m->setDisponibilite(false);
                                        $rappartement->save($m, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                } else {
                                    $trouve = false;
                                    foreach ($contrats as $c) {
                                        if ($c->getParcelle() == $m) {
                                            if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                                $this->addFlash('error', "Cette appartement est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                                $trouve = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$trouve) {
                                        $contrat->setDateContrat(new \DateTime());
                                        $contratRepository->save($contrat, true);
                                        $m->setDisponibilite(false);
                                        $rappartement->save($m, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'formc' => $formc,
        ]);
    }
    #[Route('/newva', name: 'app_contratventeappartement_new', methods: ['GET', 'POST'])]
    public function newva(Request $request, ContratRepository $contratRepository, ClientRepository $rclient, AppartementRepository $rappartement,): Response
    {
        $contrat = new Contrat();
        $client = new Client();
        $formc = $this->createForm(ClientType::class, $client);
        $contrat->setNumero($this->numcontrat($contratRepository));
        $form = $this->createForm(ContratVenteAppartementType::class, $contrat);
        $form->handleRequest($request);
        $formc->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $appartements = $rappartement->findAll();
        if ($formc->isSubmitted() && $formc->isValid()) {
            $rclient->save($client, true);
            $this->addFlash('success', 'Client enregistré avec succès!');
            return $this->redirectToRoute('app_contratmaison_new');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($appartements as $m) {
                if ($m == $contrat->getParcelle()) {
                    if ($m->getOffre()->getTypeOffre() == 'vente') {
                        if (!$m->isDisponibilite()) {
                            $this->addFlash('error', 'Cette appartement a déja été vendu!');
                            break;
                        } else {
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rappartement->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                        }
                    } else {
                        $trouve = false;
                        foreach ($contrats as $c) {
                            if ($c->getParcelle() == $m) {
                                if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                    $this->addFlash('error', "Cette appartement est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                    $trouve = true;
                                    break;
                                }
                            }
                        }
                        if (!$trouve) {
                            $contrat->setDateContrat(new \DateTime());
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rappartement->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'formc' => $formc,
        ]);
    }

    #[Route('/newm', name: 'app_contratmaison_new', methods: ['GET', 'POST'])]
    public function newm(Request $request, ContratRepository $contratRepository, ClientRepository $rclient, MaisonRepository $rmaison,): Response
    {
        $contrat = new Contrat();
        $contrat->setNumero($this->numcontrat($contratRepository));
        $client = new Client();
        $formc = $this->createForm(ClientType::class, $client);
        $form = $this->createForm(ContratMaisonType::class, $contrat);
        $form->handleRequest($request);
        $formc->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $maisons = $rmaison->findAll();
        if ($formc->isSubmitted() && $formc->isValid()) {
            $rclient->save($client, true);
            $this->addFlash('success', 'Client enregistré avec succès!');
            return $this->redirectToRoute('app_contratmaison_new');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($contrat->getDateDebut() >= $contrat->getDateFin()) {
                $this->addFlash('error', 'La date de debut du contrat ne peut pas etre postérieur ou egal à la date de fin du contrat');
            } else {
                if ($contrat->getDateDebut()->format('Y') == $contrat->getDateFin()->format('Y')) {
                    // dd($contrat->getDateDebut()->format('Y'));
                    if (((int)$contrat->getDateFin()->format('m') - (int)$contrat->getDateDebut()->format('m')) < 3) {
                        // dd($contrat->getDateDebut()->format('m'));
                        $this->addFlash('error', 'Le contrat de location doit etre au minimum de 3 mois!');
                    } else {
                        foreach ($maisons as $m) {
                            if ($m == $contrat->getMaison()) {
                                if ($m->getOffre()->getTypeOffre() == 'vente') {
                                    if (!$m->isDisponibilite()) {
                                        $this->addFlash('error', 'Cette maison a déja été vendu!');
                                        break;
                                    } else {
                                        $contrat->setUser($this->getUser());
                                        $contratRepository->save($contrat, true);
                                        $m->setDisponibilite(false);
                                        $rmaison->save($m, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                } else {
                                    $trouve = false;
                                    foreach ($contrats as $c) {
                                        if ($c->getMaison() == $m) {
                                            if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                                $this->addFlash('error', "Cette maison est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                                $trouve = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$trouve) {
                                        $contrat->setDateContrat(new \DateTime());
                                        $contrat->setUser($this->getUser());
                                        $contratRepository->save($contrat, true);
                                        $m->setDisponibilite(false);
                                        $rmaison->save($m, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'formc' => $formc,
        ]);
    }
    #[Route('/newvm', name: 'app_contratventemaison_new', methods: ['GET', 'POST'])]
    public function newvm(Request $request, ContratRepository $contratRepository, ClientRepository $rclient, MaisonRepository $rmaison,): Response
    {
        $contrat = new Contrat();
        $contrat->setNumero($this->numcontrat($contratRepository));
        $client = new Client();
        $formc = $this->createForm(ClientType::class, $client);
        $form = $this->createForm(ContratVenteMaisonType::class, $contrat);
        $form->handleRequest($request);
        $formc->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $maisons = $rmaison->findAll();
        if ($formc->isSubmitted() && $formc->isValid()) {
            $rclient->save($client, true);
            $this->addFlash('success', 'Client enregistré avec succès!');
            return $this->redirectToRoute('app_contratmaison_new');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($maisons as $m) {
                if ($m == $contrat->getMaison()) {
                    if ($m->getOffre()->getTypeOffre() == 'vente') {
                        if (!$m->isDisponibilite()) {
                            $this->addFlash('error', 'Cette maison a déja été vendu!');
                            break;
                        } else {
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rmaison->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                        }
                    } else {
                        $trouve = false;
                        foreach ($contrats as $c) {
                            if ($c->getMaison() == $m) {
                                if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                    $this->addFlash('error', "Cette maison est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                    $trouve = true;
                                    break;
                                }
                            }
                        }
                        if (!$trouve) {
                            $contrat->setDateContrat(new \DateTime());
                            $contratRepository->save($contrat, true);
                            $m->setDisponibilite(false);
                            $rmaison->save($m, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'formc' => $formc,
        ]);
    }
    #[Route('/newp', name: 'app_contratparcelle_new', methods: ['GET', 'POST'])]
    public function newp(Request $request, ContratRepository $contratRepository, ParcelleRepository $rparcelle,): Response
    {
        $contrat = new Contrat();
        $contrat->setNumero($this->numcontrat($contratRepository));
        $form = $this->createForm(ContratParcelleType::class, $contrat);
        $form->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $parcelles = $rparcelle->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($contrat->getDateDebut() >= $contrat->getDateFin()) {
                $this->addFlash('error', 'La date de debut du contrat ne peut pas etre postérieur ou egal à la date de fin du contrat');
            } else {
                if ($contrat->getDateDebut()->format('Y') == $contrat->getDateFin()->format('Y')) {
                    // dd($contrat->getDateDebut()->format('Y'));
                    if (((int)$contrat->getDateFin()->format('m') - (int)$contrat->getDateDebut()->format('m')) < 3) {
                        // dd($contrat->getDateDebut()->format('m'));
                        $this->addFlash('error', 'Le contrat de location doit etre au minimum de 3 mois!');
                    } else {
                        foreach ($parcelles as $p) {
                            if ($p == $contrat->getParcelle()) {
                                if ($p->getTerrain()->getOffre()->getTypeOffre() == 'vente') {
                                    if (!$p->isDisponibilite()) {
                                        $this->addFlash('error', 'Cette parcelle a déja été vendu!');
                                        break;
                                    } else {
                                        $contratRepository->save($contrat, true);
                                        $p->setDisponibilite(false);
                                        $rparcelle->save($p, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                } else {
                                    $trouve = false;
                                    foreach ($contrats as $c) {
                                        if ($c->getParcelle() == $p) {
                                            if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                                $this->addFlash('error', "Cette parcelle est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                                $trouve = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$trouve) {
                                        $contrat->setDateContrat(new \DateTime());
                                        $contratRepository->save($contrat, true);
                                        $p->setDisponibilite(false);
                                        $rparcelle->save($p, true);
                                        $this->addFlash('success', 'Contrat enregistré avec succès!');
                                        return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }
    #[Route('/newvp', name: 'app_contratventeparcelle_new', methods: ['GET', 'POST'])]
    public function newvp(Request $request, ContratRepository $contratRepository, ParcelleRepository $rparcelle,): Response
    {
        $contrat = new Contrat();
        $contrat->setNumero($this->numcontrat($contratRepository));
        $form = $this->createForm(ContratVenteParcelleType::class, $contrat);
        $form->handleRequest($request);
        $contrats = $contratRepository->findAll();
        $parcelles = $rparcelle->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($parcelles as $p) {
                if ($p == $contrat->getParcelle()) {
                    if ($p->getTerrain()->getOffre()->getTypeOffre() == 'vente') {
                        if (!$p->isDisponibilite()) {
                            $this->addFlash('error', 'Cette parcelle a déja été vendu!');
                            break;
                        } else {
                            $contratRepository->save($contrat, true);
                            $p->setDisponibilite(false);
                            $rparcelle->save($p, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
                        }
                    } else {
                        $trouve = false;
                        foreach ($contrats as $c) {
                            if ($c->getParcelle() == $p) {
                                if ($c->getDateFin() >= $contrat->getDateDebut()) {
                                    $this->addFlash('error', "Cette parcelle est en cours de location! et se termine le " . $c->getDateFin()->format('d F Y'));
                                    $trouve = true;
                                    break;
                                }
                            }
                        }
                        if (!$trouve) {
                            $contrat->setDateContrat(new \DateTime());
                            $contratRepository->save($contrat, true);
                            $p->setDisponibilite(false);
                            $rparcelle->save($p, true);
                            $this->addFlash('success', 'Contrat enregistré avec succès!');
                            return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }
            }
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editm', name: 'app_contratmaison_edit', methods: ['GET', 'POST'])]
    public function editm(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratMaisonType::class, $contrat);
        $form->handleRequest($request);
        $vente = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);
            if ($contrat->getMaison()->getOffre()->getTypeOffre() == 'vente') {
                return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
            }
            if ($contrat->getMaison()->getOffre()->getTypeOffre() == 'location')
                return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($contrat->getMaison()->getOffre()->getTypeOffre() == 'vente')
            $vente = true;
        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'vente' => $vente
        ]);
    }
    #[Route('/{id}/editp', name: 'app_contratparcelle_edit', methods: ['GET', 'POST'])]
    public function editp(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratParcelleType::class, $contrat);
        $form->handleRequest($request);
        $vente = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);
            if ($contrat->getParcelle()->getTerrain()->getOffre()->getTypeOffre() == 'vente') {
                return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
            }
            if ($contrat->getParcelle()->getTerrain()->getOffre()->getTypeOffre() == 'location')
                return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($contrat->getParcelle()->getTerrain()->getOffre()->getTypeOffre() == 'vente')
            $vente = true;
        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'vente' => $vente,
        ]);
    }
    #[Route('/{id}/edita', name: 'app_contratappartement_edit', methods: ['GET', 'POST'])]
    public function edita(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratAppartementType::class, $contrat);
        $form->handleRequest($request);
        $vente = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);
            if ($contrat->getAppartement()->getImmeuble()->getOffre()->getTypeOffre() == 'vente') {
                return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
            }
            if ($contrat->getAppartement()->getImmeuble()->getOffre()->getTypeOffre() == 'location')
                return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($contrat->getAppartement()->getImmeuble()->getOffre()->getTypeOffre() == 'vente')
            $vente = true;
        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
            'vente' => $vente,
        ]);
    }
    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat = null): Response
    {
        if ($contrat) {
            return $this->render('contrat/show.html.twig', [
                'contrat' => $contrat,
            ]);
        } else {
            $this->addFlash('error', 'id n\'existe pas');
            return $this->redirectToRoute('app_contratlocation_index');
        }
    }
    #[Route('/{id}', name: 'app_contratmaison_show', methods: ['GET'])]
    public function showm(Contrat $contrat = null): Response
    {
        if ($contrat) {
            return $this->render('contrat/show.html.twig', [
                'contrat' => $contrat,
            ]);
        } else {
            $this->addFlash('error', 'id n\'existe pas');
            return $this->redirectToRoute('app_contratlocation_index');
        }
    }
    #[Route('/{id}', name: 'app_contratapprtement_show', methods: ['GET'])]
    public function showa(Contrat $contrat = null): Response
    {
        if ($contrat) {
            return $this->render('contrat/show.html.twig', [
                'contrat' => $contrat,
            ]);
        } else {
            $this->addFlash('error', 'id n\'existe pas');
            return $this->redirectToRoute('app_contratlocation_index');
        }
    }
    #[Route('/{id}', name: 'app_contratparcelle_show', methods: ['GET'])]
    public function showp(Contrat $contrat = null): Response
    {
        if ($contrat) {
            return $this->render('contrat/show.html.twig', [
                'contrat' => $contrat,
            ]);
        } else {
            $this->addFlash('error', 'id n\'existe pas');
            return $this->redirectToRoute('app_contratlocation_index');
        }
    }
    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contrat->getId(), $request->request->get('_token'))) {
            if ($contrat->getMaison() != null)
                $redirect = ($contrat->getMaison()->getOffre() == "vente") ? true : false;
            if ($contrat->getAppartement() != null)
                $redirect = ($contrat->getAppartement()->getImmeuble()->getOffre() == "vente") ? true : false;
            if ($contrat->getParcelle() != null)
                $redirect = ($contrat->getParcelle()->getTerrain()->getOffre() == "vente") ? true : false;
            $contratRepository->remove($contrat, true);
        }
        if ($redirect)
            return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
        else
            return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
    }
    /*if (($contrat->getParcelle() != null && $contrat->getParcelle()->getTerrain()->getOffre()->getTypeOffre() == "vente") || ($contrat->getAppartement() != null && $contrat->getAppartement()->getImmeuble()->getOffre()->getTypeOffre() == "vente") || ($contrat->getMaison() != null && $contrat->getMaison()->getOffre()->getTypeOffre() == "vente"))
return $this->redirectToRoute('app_contratvente_index', [], Response::HTTP_SEE_OTHER);
if (($contrat->getParcelle() != null && $contrat->getParcelle()->getTerrain()->getOffre()->getTypeOffre() == "location") || ($contrat->getAppartement() != null && $contrat->getAppartement()->getImmeuble()->getOffre()->getTypeOffre() == "location") || ($contrat->getMaison() != null && $contrat->getMaison()->getOffre()->getTypeOffre() == "location"))
return $this->redirectToRoute('app_contratlocation_index', [], Response::HTTP_SEE_OTHER);
*/
}
