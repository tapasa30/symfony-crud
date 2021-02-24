<?php

namespace App\Service;

use App\Document\VOD;

class VODService
{
    public function toArray(VOD $vod): array {
        return [
            'id' => $vod->getId(),
            'title' => $vod->getTitle(),
            'genre' => $vod->getGenre(),
            'duration' => $vod->getDuration(),
            'image' => $vod->getImage(),
            'synopsis' => $vod->getSynopsis(),
        ];
    }
}