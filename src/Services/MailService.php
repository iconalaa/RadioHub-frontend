<?php

namespace App\Services;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(): Response
    {

        
        $email = (new Email())
            ->from('wifekarfaoui0@gmail.com')
            ->to('rahalinermine@gmail.com')
            ->subject('appointment confirmation')
            ->text('your appointment is confirmed ');

        $this->mailer->send($email);

        // Retourne une réponse vide ou une redirection selon votre logique
        return new Response('E-mail envoyé avec succès !');
    }

    /*
    public function sendNewProposalNotification(string $recipientEmail, string $proposalDetails)
    {
        $email = (new Email())
            ->from('mohamedyacine.chrigui@etudiant-enit.utm.tn')
            ->to($recipientEmail)
            ->subject('Nouvelle proposition créée')
            ->text('Une nouvelle proposition a été créée avec les détails suivants: ' . $proposalDetails);

        $this->mailer->send($email);
    }*/

}