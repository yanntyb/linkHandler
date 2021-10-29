<?php

namespace Yanntyb\App\Model\Classes\Entity;

use JetBrains\PhpStorm\Pure;

class Image
{
    private string $imgLink;

    public function __construct(Link $link){
        $this->imgLink = $this->thumbalizr($link);
    }

    public function thumbalizr(Link $link): string
    {
        if($link->getUser()->getApisecret() !== "0") {
            if (!file_exists("/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}")) {
                $embed_key = $link->getUser()->getApikey(); # replace it with you Embed API key
                $secret = $link->getUser()->getApisecret(); # replace it with your Secret

                $query = 'url=' . urlencode($link->getHref());

                $token = md5($query . $secret);

                file_put_contents("/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}", file_get_contents("https://api.thumbalizr.com/api/v1/embed/{$embed_key}/{$token}/?{$query}"));
            }
            return "/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}";
        }
        else{
            if(file_exists("/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}")){
                return "/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}";
            }
            return "/assets/misc/img.png";
        }

    }

    /**
     * @return string
     */
    public function getImgLink(): string
    {
        return $this->imgLink;
    }

}