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

/**
 * @Route("/back/artwork")
 */
class ArtworkController extends AbstractController
{
    /**
     * @Route("/", name="app_back_artwork_index", methods={"GET"})
     */
    public function index(ArtworkRepository $artworkRepository): Response
    {
        return $this->render('back/artwork/index.html.twig', [
            'artworks' => $artworkRepository->findAll(),
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
                // Lire le fichier et le convertir en base64
                $pictureData = base64_encode(file_get_contents($pictureFile->getPathname()));
                $artwork->setPicture($pictureData);

                // Réduire la taille de l'image tout en conservant le ratio d'aspect
                $imagine = new Imagine();
                $image = $imagine->open($pictureFile->getPathname());
                $size = $image->getSize();

                $width = 300;
                $height = (int) ($size->getHeight() * ($width / $size->getWidth()));
                $box = new Box($width, $height);

                $image->resize($box);

                // Déterminer l'extension du fichier en utilisant MimeTypes
                $mimeTypes = new MimeTypes();
                $extension = $mimeTypes->getExtensions($pictureFile->getMimeType())[0] ?? 'jpeg';

                // Sauvegarder l'image avec l'extension correcte
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $extension;
                $image->save($tempPath);

                // Lire le fichier redimensionné et le convertir en base64
                $pictureMinData = base64_encode(file_get_contents($tempPath));
                $artwork->setPictureMin($pictureMinData);

                // Supprimer le fichier temporaire
                unlink($tempPath);
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
    public function edit($id,Request $request,TagRepository $tagRepository, Artwork $artwork, ArtworkRepository $artworkRepository): Response
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
