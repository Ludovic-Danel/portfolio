<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtworkRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(ArtworkRepository $ArtworkRepository): Response
    {
        $allArtwork = $ArtworkRepository->findAll();

        return $this->render('main/index.html.twig', [
           'artworks' => $allArtwork
        ]);
    }
}
