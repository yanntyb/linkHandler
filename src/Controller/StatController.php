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

        $outputName = [];

        foreach($var["by_name"] as $data){
            $outputName[$data[0]->getHref()] = 0;
            foreach(array_slice($data,1) as $single){
                $outputName[$data[0]->getHref()]++;
            }
        }


        $outputUser = [];
        foreach($var["by_user"] as $data){
            $outputUser[$data[0]->getUser()->getMail()] = 0;
            foreach(array_slice($data,1) as $single){
                $outputUser[$data[0]->getUser()->getMail()]++;
            }
        }

        $output = [
            "by_name" => array_reverse($outputName),
            "by_user" => array_reverse($outputUser),
        ];

        echo json_encode($output);
    }

    public function renderSingle(int $id){
        $link = (new LinkManager)->getSingleEntity($id);
        echo json_encode([
            "user" => $link->getUser()->getMail(),
            "clic" => $link->getUsed()
        ]);
    }
}