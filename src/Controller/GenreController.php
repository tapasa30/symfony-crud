<?php
namespace App\Controller;

use App\Document\VOD;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends Response
{
    /**
     * @Route("/g_G", methods={"POST"})
     */
    public function listAction(DocumentManager $dm)
    {
        $vodRepository = $dm->getRepository(VOD::class);
        $genres = [];

        foreach ($vodRepository->findAll() as $vod) {
            if (!empty($vod->getGenre()) && !in_array($vod->getGenre(), $genres)) {
                $genres[] = $vod->getGenre();
            }
        }

        return $this->response([
            'genres' => $genres
        ]);
    }
}