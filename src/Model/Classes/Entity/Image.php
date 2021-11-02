<?php

namespace Yanntyb\App\Model\Classes\Entity;

use Yanntyb\App\Model\Classes\Manager\LinkManager;

class Image
{
    private string $imgLink;

    public function __construct(Link $link){
        $this->imgLink = $this->thumbalizr($link);
    }

    public function thumbalizr(Link $link): string
    {
        if($link->getUser()->getApisecret() !== "0") {
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] ."/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png")) {
                $embed_key = $link->getUser()->getApikey();
                $secret = $link->getUser()->getApisecret();

                $query = 'url=' . urlencode($link->getHref());

                $token = md5($query . $secret);

                file_put_contents($_SERVER["DOCUMENT_ROOT"] ."/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png", file_get_contents("https://api.thumbalizr.com/api/v1/embed/{$embed_key}/{$token}/?{$query}"));
            }
            return "/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png";
        }
        else{
            if(file_exists($_SERVER["DOCUMENT_ROOT"] ."/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png")){
                return "/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png";
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