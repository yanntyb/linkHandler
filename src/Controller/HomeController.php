<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Manager\LinkManager;
use Yanntyb\App\Model\Traits\RenderViewTrait;

class HomeController
{
    use RenderViewTrait;

    public function render_home($user){
        $manager = new LinkManager();
        if($user->getAdmin() === 1){
            $links = $manager->getAllEntity();
        }
        else{
            $links = $manager->getAllEntity("user_fk",$user->getId());
        }
        if(isset($_GET["error"])){
            $var = [
                "links" => $links,
                "error" => $_GET["error"]
            ];
        }
        else{
            $var = [
                "links" => $links
            ];
        }
        $this->render("Home/home","Links Handler", $var);
    }

    public function render_login(){
        $this->render("Home/login", "Login");
    }
}