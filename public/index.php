<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

require "../vendor/autoload.php";

use Yanntyb\App\Controller\FormController;
use Yanntyb\App\Controller\LinkController;
use Yanntyb\App\Controller\UserController;

use Yanntyb\App\Controller\HomeController;

session_start();

if(isset($_GET["page"])){
    switch($_GET["page"]){
        case "home":
            $controller = new HomeController;
            if(isset($_GET["sub"])){
                switch($_GET["sub"]){
                    case "render":
                        if(isset($_SESSION["user"])) {
                            $user = unserialize($_SESSION["user"]);
                            $controller->render_home($user);
                        }
                        else{
                            header("Location: index.php?page=home&sub=login");
                        }
                        break;
                    case "add":
                        if(isset($_SESSION["user"])){
                            (new LinkController)->render_form();
                        }
                        else{
                            header("Location: index.php");
                        }
                        break;
                    case "login":
                        $controller->render_login();
                        break;
                    case "deco":
                        unset($_SESSION["user"]);
                        header("Location: index.php");
                }
            }
            else{
                header("Location: index.php?page=home&sub=render");
            }
            break;
        case "form":
            if(isset($_GET["sub"])) {
                switch ($_GET["sub"]) {
                    case "link":
                        if(isset($_SESSION["user"])){
                            $user = unserialize($_SESSION["user"]);
                            $keys = ["href", "title", "target", "name"];
                            if((new FormController)->checkForm($_POST, $keys)){
                                if(!(new LinkController)->add_link($_POST, $keys,$user->getId())){
                                    header("Location: index.php?page=home&error=Lien invalide&sub=render");
                                    break;
                                };
                                header("Location: index.php");
                                break;
                            }
                            else{
                                header("Location: index.php?page=home&sub=add");
                            }
                        }
                        else{
                            header("Location: index.php?page=home&sub=add");
                        }
                        break;
                    case "login":
                        $keys = ["mail", "pass"];
                        if((new FormController)->checkForm($_POST,$keys)){
                            $user = (new UserController)->check_login($_POST,$keys);
                            if($user){
                                $_SESSION['user'] = serialize($user);
                                header("Location: index.php?page=home");
                            }
                            else{
                                header("Location: index.php?page=home&sub=login");
                            }
                        }
                        break;
                    case "link_edit":
                        $keys = ["href", "title", "target", "name", "id"];
                        if((new FormController)->checkForm($_POST, $keys)){
                            (new LinkController)->modify_link($_POST, $keys);
                            header("Location: index.php?page=home");
                        }
                        else{
                            header("Location: index.php?page=home&sub=add");
                        }
                        break;
                }
            }
            break;
        case "action":
            $controller = new LinkController;
            if(isset($_GET["sub"])) {
                if(isset($_GET["id"])){
                    $link = $controller->exist($_GET["id"]);
                    if($link){
                        if(isset($_SESSION["user"])){
                            $user = unserialize($_SESSION["user"]);
                            switch ($_GET["sub"]){
                                case "delete":
                                    if($link->getUser()->getId() === $user->getId() || $user->getAdmin() === 1){
                                        $controller->delete($link);
                                    }
                                    header("Location: index.php");
                                    break;
                                case "edit":
                                    if($link->getUser()->getId() === $user->getId() || $user->getAdmin() === 1){
                                        $controller->render_edit($link);
                                    }
                                    else{
                                        header("Location: index.php");
                                    }
                                    break;
                            }
                        }
                        else{
                            header("Location: index.php");
                        }
                    }
                    else{
                        header("Location: index.php");
                    }
                }

            }

    }
}
else{
    header("Location: index.php?page=home");
}