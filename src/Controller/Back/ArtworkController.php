<?php

namespace App\Controller\Back;

use App\Entity\Artwork;
use App\Entity\Tag;
use App\Form\ArtworkType;
use App\Form\TagType;
use App\Repository\ArtworkRepository;
use App\Repository\TagRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Mime\MimeTypes;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/back/artwork")
 */
class ArtworkController extends AbstractController
{
    /**
     * @Route("/", name="app_back_artwork_index", methods={"GET"})
     */
    public function index(ArtworkRepository $artworkRepository, PaginatorInterface $paginator, Request $request): Response
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
            return $this->render('partials/artworksIndex.html.twig', [
                'pagination' => $pagination,
            ]);
        }


        return $this->render('back/artwork/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="app_back_artwork_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TagRepository $tagRepository, ArtworkRepository $artworkRepository): Response
    {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                // Initialiser Imagine
                $imagine = new Imagine();
                $image = $imagine->open($pictureFile->getPathname());

                // Déterminer l'extension du fichier en utilisant MimeTypes
                $mimeTypes = new MimeTypes();
                $extension = 'webp'; // Convertir en WebP

                // Sauvegarder l'image en WebP avec une qualité réduite à 75%
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $extension;
                $options = ['webp_quality' => 75]; // Réduire la qualité à 75%
                $image->save($tempPath, $options);

                // Lire le fichier converti et le convertir en base64
                $pictureData = base64_encode(file_get_contents($tempPath));
                $artwork->setPicture($pictureData);

                // Redimensionner l'image à une largeur de 500 pixels tout en conservant le ratio d'aspect
                $size = $image->getSize();
                $width = 500;
                $height = (int) ($size->getHeight() * ($width / $size->getWidth()));
                $box = new Box($width, $height);

                $image->resize($box);

                // Sauvegarder l'image redimensionnée avec l'extension WebP
                $tempPathMin = sys_get_temp_dir() . '/' . uniqid() . '.' . $extension;
                $image->save($tempPathMin, $options);

                // Lire le fichier redimensionné et le convertir en base64
                $pictureMinData = base64_encode(file_get_contents($tempPathMin));
                $artwork->setPictureMin($pictureMinData);

                // Supprimer les fichiers temporaires
                unlink($tempPath);
                unlink($tempPathMin);
            }

            $artworkRepository->add($artwork, true);
            return $this->redirectToRoute('app_back_artwork_index', [], Response::HTTP_SEE_OTHER);
        }
        $tag = new Tag();
        $form2 = $this->createForm(TagType::class, $tag);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $tagRepository->add($tag, true);

            return $this->redirectToRoute('app_back_artwork_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/artwork/new.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
            'tag' => $tag,
            'form2' => $form2,

        ]);
    }    /**
     * @Route("/{id}", name="app_back_artwork_show", methods={"GET"})
     */
    public function show(Artwork $artwork): Response
    {
        return $this->render('back/artwork/show.html.twig', [
            'artwork' => $artwork,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_artwork_edit", methods={"GET", "POST"})
     */
    public function edit($id, Request $request, TagRepository $tagRepository, Artwork $artwork, ArtworkRepository $artworkRepository): Response
    {
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artworkRepository->add($artwork, true);

            return $this->redirectToRoute('app_back_artwork_index', [], Response::HTTP_SEE_OTHER);
        }
        $tag = new Tag();
        $form2 = $this->createForm(TagType::class, $tag);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $tagRepository->add($tag, true);

            return $this->redirectToRoute('app_back_artwork_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
            'tag' => $tag,
            'form2' => $form2,

        ]);
    }

    /**
     * @Route("/{id}", name="app_back_artwork_delete", methods={"POST"})
     */
    public function delete(Request $request, Artwork $artwork, ArtworkRepository $artworkRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artwork->getId(), $request->request->get('_token'))) {
            $artworkRepository->remove($artwork, true);
        }

        return $this->redirectToRoute('app_back_artwork_index', [], Response::HTTP_SEE_OTHER);
    }
}
