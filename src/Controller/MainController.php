<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtworkRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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
           'tags' => $allTags
        ]);
    }

    /**
    * @Route("/artwork/{id}", name="app_show", requirements={"id": "\d+"})
    */
    public function show($id, ArtworkRepository $ArtworkRepository): Response
    {
        $Artwork = $ArtworkRepository->find($id);
        // ! si l'artwork  n'existe pas : 404
        if ($Artwork === null) {
            throw $this->createNotFoundException("cet artwork n'existe pas");
        }

        return $this->render('main/show.html.twig', [
            'artwork' => $Artwork,
         ]);

    }
    /**
     * @route("/tag/{id}", name="app_tag", requirements={"id": "\d+"})
     *
     * @return Response
     */
    public function tag($id, TagRepository $TagRepository): Response
    {
        $tag = $TagRepository->find($id);

        $Artworks = $TagRepository->findArtworksByTagId($id);

        return $this->render('main/tag.html.twig', [
            'tag' => $tag,
            'artworkTags' => $Artworks
         ]);

    }
    /**
     * @route("/category", name="app_category")
     *
     * @param TagRepository $TagRepository
     * @return Response
     */
    public function category(TagRepository $TagRepository): Response
    {
        $allTags = $TagRepository->findAll();

        return $this->render('main/category.html.twig', [
                'tags' => $allTags,
             ]);

    }
/**
     * @Route("/artworks", name="app_artworks")
     *
     * @param ArtworkRepository $artworkRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function artworks(ArtworkRepository $artworkRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $artworkRepository->createQueryBuilder('a')
        ->orderBy('a.drawingCreatedAt', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // numéro de la page en cours
            10 // nombre d'éléments par page
        );

        return $this->render('main/artworks.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
