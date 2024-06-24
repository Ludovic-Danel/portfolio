<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtworkRepository;
use App\Repository\TagRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(ArtworkRepository $ArtworkRepository, TagRepository $TagRepository): Response
    {
        $allArtwork = $ArtworkRepository->findLatestArtworks();

        $allTags = $TagRepository->findAll();

        return $this->render('main/index.html.twig', [
           'artworks' => $allArtwork,
           'tags'=> $allTags
        ]);
    }

    
}
