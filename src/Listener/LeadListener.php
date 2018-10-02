<?php

namespace App\Listener;

use App\Entity\Lead;
use Twig\Environment as TwigEnvironment;

class LeadListener
{
    private $twig;
    private $mailer;

    public function __construct(TwigEnvironment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function prePersist(Lead $lead)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($lead->getEmail())
            ->setBody(
                $this->twig->render(
                    'registration.html.twig',
                    array('lead' => $lead)
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}