<?php

namespace App\Controller\Front;

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
     * @Route("/", name="app_front")
     */
    public function index(ArtworkRepository $ArtworkRepository, TagRepository $TagRepository): Response
    {
        $allArtwork = $ArtworkRepository->findLatestArtworks();

        $allTags = $TagRepository->findAll();

        return $this->render('front/index.html.twig', [
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
            throw $this->createNotFoundException("Ce dessin n'existe pas");
        }

        return $this->render('front/show.html.twig', [
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

        return $this->render('front/tag.html.twig', [
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

        return $this->render('front/category.html.twig', [
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
            6 // nombre d'éléments par page
        );
        if ($request->isXmlHttpRequest()) {
            // Si la requête est une requête AJAX, renvoyez uniquement le contenu nécessaire
            return $this->render('partials/artworks.html.twig', [
                'pagination' => $pagination,
            ]);
        }
        return $this->render('front/artworks.html.twig', [
            'pagination' => $pagination,
        ]);
    }

     /**
     * @route("/about", name="app_about")
     *
     * 
     * @return Response
     */
    public function about(): Response
    {

        return $this->render('front/about.html.twig', [
             ]);

    }

}
