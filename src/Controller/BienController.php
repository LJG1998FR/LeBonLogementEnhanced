<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Image;
use App\Form\BienType;
use App\Repository\BienRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bien')]
class BienController extends AbstractController
{
    #[Route('/', name: 'bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository, ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/all', name: 'bien_all_index', methods: ['GET'])]
    public function indexall(BienRepository $bienRepository, ImageRepository $imageRepository): Response
    {
        return $this->render('bien/indexall.html.twig', [
            'biens' => $bienRepository->findAll(),
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'bien_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();
            
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = uniqid().'.'.$image->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $destination,
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setUrl($fichier);
                $bien->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $bien->setProprietaire($this->getUser());
            $entityManager->persist($bien);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add(
                'bien',
                'Bien ajouté avec succès!'
            );


            return $this->redirectToRoute('bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'bien_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Bien $bien): Response
    {
        if ( $bien->getProprietaire() != $this->getUser() ) {
            return $this->redirectToRoute('bien_index');
        }
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = uniqid().'.'.$image->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $destination,
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setUrl($fichier);
                $bien->addImage($img);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
                'bien',
                'Modifications enregistrées!'
            );

            return $this->redirectToRoute('bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien): Response
    {
        $image = new Image();
        if ( $bien->getProprietaire() != $this->getUser() ) {
            return $this->redirectToRoute('bien_index');
        }
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {

            $images = $bien->getImages();

            // On boucle sur les images
            foreach($images as $image){

                $bien->removeImage($image);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($image);
            }

            $entityManager->remove($bien);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add(
                'bien',
                'Bien supprimé avec succès!'
            );
        }

        return $this->redirectToRoute('bien_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/supprimer/image/{id}', name: 'bien_delete_image', methods: ["DELETE"])]
    public function deleteImage(Image $image, Request $request){
        $bien = new Bien();
        if ( $bien->getProprietaire() != $this->getUser() ) {
            return $this->redirectToRoute('bien_index');
        }
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getUrl();
            // On supprime le fichier
            unlink($this->getParameter('app.path.biens_images').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

}