<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Entity\Image;
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
        $var = [
            "link" => $link,
        ];
        $this->render("Link/edit","edit",$var);
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

    /**
     * Return true if the url is real
     * @param string $link
     * @return bool|void
     */
    public function checkIfLinkIsReal(string $link){
        $url_status = UrlStatus::get($link);
        $http_status_code = $url_status->getStatusCode();
        if($http_status_code == 200){
            return true;
        }
    }

    public function renderAll(){
        $links = (new LinkManager)->getAllEntity();
        $output = [];
        /**
         * @var Link $link
         */
        foreach($links as $link){
            $user = $link->getUser();
            $output[] = [
                "href" => $link->getHref(),
                "title" => $link->getTitle(),
                "target" => $link->getTarget(),
                "name" => $link->getName(),
                "id" => $link->getId(),
                "img" => (new Image($link))->getImgLink(),
                "user" => [
                    "mail" => $user->getMail(),
                    "id" => $user->getId(),
                ],
            ];
        }
        echo json_encode($output);
    }

    public function renderConnected($userId){
        $links = (new LinkManager)->getAllEntity("user_fk",$userId);
        $output = [];
        /**
         * @var Link $link
         */
        foreach($links as $link){
            $user = $link->getUser();
            $output[] = [
                "href" => $link->getHref(),
                "title" => $link->getTitle(),
                "target" => $link->getTarget(),
                "name" => $link->getName(),
                "id" => $link->getId(),
                "img" => (new Image($link))->getImgLink(),
                "user" => [
                    "mail" => $user->getMail(),
                    "id" => $user->getId(),
                ],
            ];
        }
        echo json_encode($output);
    }
}