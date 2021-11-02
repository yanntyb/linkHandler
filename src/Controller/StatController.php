<?php

namespace Yanntyb\App\Controller;

use SSD\Sorter\Sorter;
use Yanntyb\App\Model\Classes\Manager\LinkManager;

class StatController
{

    public function renderAll(){
        $links = (new LinkManager)->getAllEntity();
        $name = [];
        $usersLinks = [];
        foreach($links as $link){
            if(!key_exists($link->getHref(),$name)){
                $name[$link->getHref()]["count"] = 1;
            }
            else{
                $name[$link->getHref()]["count"] ++;
            }
            $name[$link->getHref()][] = $link;

            if(!key_exists($link->getUser()->getMail(),$usersLinks)){
                $usersLinks[$link->getUser()->getMail()]["count"] = 1;
            }
            else{
                $usersLinks[$link->getUser()->getMail()]["count"] ++;
            }
            $usersLinks[$link->getUser()->getMail()][] = $link;
        }


        $var = [
            "by_name" => (new Sorter($name))->asc("count")->collection(),
            "by_user" => (new Sorter($usersLinks))->asc("count")->collection(),
        ];

        dump($name);

        dump($var);
    }
}