<?php

namespace App\TokenExtractor;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorizationRequestTokenExtractor implements TokenExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract(Request $request)
    {
        $request = json_decode($request->getContent(), true);
        $token = $request['token'] ?? null;

        return !empty($token) ? $token : false;
    }
}
