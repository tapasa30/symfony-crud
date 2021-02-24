<?php

namespace App\EventListener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    private $documentManager;

    public function __construct(DocumentManager $dm)
    {
        $this->documentManager = $dm;
    }

    public function __invoke(AuthenticationSuccessEvent $event)
    {
        $eventData = $event->getData();
        $user = $event->getUser();

        $user->setToken($eventData['token']);

        $this->documentManager->persist($user);
        $this->documentManager->flush();

        $event->setData([
            'error' => false,
            'message' => '',
            'data' => $eventData
        ]);
    }
}