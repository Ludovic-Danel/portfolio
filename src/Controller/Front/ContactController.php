<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Create and send the email
            $email = (new Email())
                ->from($data['email'])
                ->to('l.danel59@gmail.com')
                ->subject('Nouveau message de contact')
                ->text('Vous avez reçu un nouveau message de ' . $data['name'] . ':</br> ' . $data['message']);

            $mailer->send($email);

            // Add a flash message and redirect
            $this->addFlash('success', 'Message envoyé avec succès !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
