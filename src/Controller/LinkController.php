<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Manager\LinkManager;
use Yanntyb\App\Model\Traits\RenderViewTrait;

class LinkController
{

    use RenderViewTrait;

    public function add_link($var, array $keys){
        $tab = [];
        $manager = new LinkManager;
        foreach($keys as $key){
            $tab["$key"] = $manager->sanitize($var[$key]);
        }
        $manager->newLink($tab);
    }
}