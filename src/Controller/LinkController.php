<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Entity\link;
use Yanntyb\App\Model\Classes\Entity\User;
use Yanntyb\App\Model\Classes\Manager\LinkManager;
use Yanntyb\App\Model\Traits\RenderViewTrait;

use Muffeen\UrlStatus\UrlStatus;

class LinkController
{

    use RenderViewTrait;

    /**
     * Add link to db
     * @param $var
     * @param array $keys
     * @param $user_id
     * @return bool
     */
    public function add_link($var, array $keys, $user_id): bool
    {
        $tab = [];
        $manager = new LinkManager;
        foreach($keys as $key){
            $tab["$key"] = $manager->sanitize($var[$key]);
        }
        if($this->checkIfLinkIsReal($tab["href"])){
            $manager->newLink($tab, $user_id);
            return true;
        }
        return false;

    }

    public function delete(Link $link){
        $manager = new LinkManager;
        $manager->delete($link->getId());
    }

    public function exist(int $id){
        $manager = new LinkManager;
        $link = $manager->getSingleEntity($id);
        if($link){
            return $link;
        }
        return false;
    }

    public function render_edit(Link $link){
        $this->render("Link/edit","edit",$link);
    }

    public function render_form(){
        $this->render("Link/add","Add a link");
    }

    public function modify_link($var, array $keys){
        $tab = [];
        $manager = new LinkManager;
        foreach($keys as $key){
            $tab["$key"] = $manager->sanitize($var[$key]);
        }
        if($this->checkIfLinkIsReal($tab["href"])){
            $manager->edit($tab);
        }
    }

    public function checkIfLinkIsReal(string $link){
        $url_status = UrlStatus::get($link);
        $http_status_code = $url_status->getStatusCode();
        if($http_status_code == 200){
            return true;
        }
    }
}