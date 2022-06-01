<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/{email}', name: 'email')]
    public function sendEmail(MailerInterface $mailer, Utilisateur $user): Response
    {
        $email = (new Email())
            ->from('ljgalt1@gmail.com')
            ->to($user->getEmail())
            ->subject('Test Mail!')
            ->html($this->renderView('emails/sendmailtest.html.twig'));

        $mailer->send($email);
        $this->get('session')->getFlashBag()->add(
            'bien',
            'Mail envoyé !'
        );

        return $this->redirectToRoute('bien_index', [], Response::HTTP_SEE_OTHER);
    }
}