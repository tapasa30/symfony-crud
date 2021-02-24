<?php

namespace App\Security\Guard;

namespace App\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class JWTTokenAuthenticator extends BaseAuthenticator
{
    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher, TokenExtractorInterface $tokenExtractor, TokenStorageInterface $preAuthenticationTokenStorage)
    {
        parent::__construct($jwtManager, $dispatcher, $tokenExtractor, $preAuthenticationTokenStorage);
    }
}