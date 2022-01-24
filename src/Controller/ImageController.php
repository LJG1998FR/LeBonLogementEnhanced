<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}', name: 'image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $nom = $image->getUrl();
            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('app.path.biens_images').'/'.$nom);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add(
                'bien',
                'Image supprimée avec succès!'
            );
        }

        return $this->redirectToRoute('bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
