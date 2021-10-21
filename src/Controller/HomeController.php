<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Manager\LinkManager;
use Yanntyb\App\Model\Traits\RenderViewTrait;

class HomeController
{
    use RenderViewTrait;

    public function render_home(){
        $links = (new LinkManager)->getAllEntity();
        $this->render("Home/home","Links Handler", $links);
    }

    public function render_form(){
        $this->render("Home/add","Add a link");
    }

    public function render_login(){
        $this->render("Home/login", "Login");
    }
}