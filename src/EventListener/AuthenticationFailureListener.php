<?php

namespace App\EventListener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener
{
    private $documentManager;

    public function __construct(DocumentManager $dm)
    {
        $this->documentManager = $dm;
    }

    public function __invoke(AuthenticationFailureEvent $event)
    {
        $event->setResponse(new JsonResponse([
            'error' => false,
            'message' => '',
            'data' => []
        ]));
    }
}