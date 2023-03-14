<?php

namespace App\Controller;

use App\Repository\AppartementRepository;
use App\Repository\ImmeubleRepository;
use App\Repository\MaisonRepository;
use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(MaisonRepository $rmaison, ImmeubleRepository $rimmeuble, TerrainRepository $rterrain, ): Response
    {
        $maisons = $rmaison->findAll();
        $im = null;
        $t = null;
        $m = null;
        if ($maisons){
            for($i = count($maisons)-3; $i < count($maisons); $i++){
                $m[] = $maisons[$i];
            }
        }
        
        $immeubles = $rimmeuble->findAll();
        if($immeubles){
            for($i = count($immeubles)-3; $i < count($immeubles); $i++){
                $im[] = $immeubles[$i];
            }
        }
        $terrains = $rterrain->findAll();
        if ($terrains) {
            for($i = count($terrains)-3; $i < count($terrains); $i++){
                $t[] = $terrains[$i];
            }
        }
        
        // dd($m[2]);
        return $this->render('home/index.html.twig', [
            "m" => $m,
            "im" => $im,
            "t" => $t,
        ]);
    }

    #[Route('/all', name: 'app_all', methods: ['GET'])]
    public function all(MaisonRepository $rmaison, ImmeubleRepository $rimmeuble, TerrainRepository $rterrain, AppartementRepository $rappartement): Response
    {
        return $this->render('home/all.html.twig', [
            'maisons' => $rmaison->findAll(),
            'immeubles' => $rimmeuble->findAll(),
            'appartements' => $rappartement->findAll(),
            'terrains' => $rterrain->findAll(),
        ]);
    }
}