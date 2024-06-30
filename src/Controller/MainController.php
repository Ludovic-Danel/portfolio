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

     /**
     * @Route("/artwork/{id}", name="app_show", requirements={"id": "\d+"})
     */
    public function  show($id, ArtworkRepository $ArtworkRepository) :Response
    {
        $Artwork = $ArtworkRepository->find($id);
        // ! si l'artwork  n'existe pas : 404
        if ($Artwork === null){ throw $this->createNotFoundException("cet artwork n'existe pas");}

        return $this->render('main/show.html.twig', [
            'artwork' => $Artwork,
         ]);
 
    }    
}
