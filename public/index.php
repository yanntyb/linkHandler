<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

require "../vendor/autoload.php";

use Yanntyb\App\Controller\FormController;
use Yanntyb\App\Controller\LinkController;
use Yanntyb\App\Controller\StatController;
use Yanntyb\App\Controller\UserController;

use Yanntyb\App\Controller\HomeController;
use Yanntyb\App\Model\Classes\Manager\LinkManager;

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
                    case "profil":
                        if(isset($_SESSION["user"])) {
                            $user = unserialize($_SESSION["user"]);
                            $controller->render_profile($user);
                        }
                        else{
                            header("Location: index.php?page=home&sub=login");
                        }
                        break;
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
                            $user = (new UserController)->check_login($_POST);
                            if($user){
                                $_SESSION['user'] = serialize($user);
                                header("Location: index.php?page=home");
                            }
                            else{
                                header("Location: index.php?page=home&sub=login");
                            }
                        }
                        break;
                    case "register":
                        $keys = ["mail", "pass", "re_pass"];
                        if((new FormController)->checkForm($_POST,$keys)){
                            $user = (new UserController)->register($_POST);
                            if($user){
                                $_SESSION["user"] = serialize($user);
                                header("Location: index.php?page=home");
                            }
                            else{
                                header("Location: index.php?page=login");
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
                    case "edit_profil":
                        if(isset($_SESSION["user"])){
                            $user = unserialize($_SESSION["user"]);
                            //Change pass
                            $keys = ["newPass", "oldPass"];
                            if((new FormController)->checkForm($_POST, $keys)){
                                (new UserController)->changePass($user, $_POST["newPass"], $_POST["oldPass"]);
                            }

                            //Change Embeb Api Key
                            $keys = ["apiKey"];
                            if((new FormController)->checkForm($_POST, $keys)){
                                $_SESSION["user"] = serialize((new UserController)->changeApiKey($user,$_POST["apiKey"]));
                            }

                            //Change Api Secret
                            $keys = ["apiSecret"];
                            if((new FormController)->checkForm($_POST,$keys)){
                                $_SESSION["user"] = serialize((new UserController)->changeApiSecret($user,$_POST["apiSecret"]));
                            }

                            header("Location: index.php");
                        }
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
            break;
        case "stat":
            if(isset($_SESSION["user"])){
                $user = unserialize($_SESSION["user"]);
                if(isset($_GET["sub"])){
                    switch ($_GET["sub"]){
                        case "render":
                            $controller = new StatController();
                            $controller->renderAll();
                            break;
                        default:
                            header("Location: index.php?page=stat&sub=render");
                            break;
                    }
                }
                else{
                    header("Location: index.php?page=stat&sub=render");
                }
            }
            else{
                header("Location: index.php");
            }
            break;
        case "info":
            if(isset($_GET["sub"])){
                switch ($_GET["sub"]){
                    case "role":
                        if(isset($_SESSION["user"])){
                            $user = unserialize($_SESSION["user"]);
                            echo json_encode(["role" => $user->getAdmin()]);
                        }
                        else{
                            echo json_encode(["role" => "notConnected"]);
                        }
                        break;
                    case "info":
                        $controller = new LinkController();
                        if(isset($_GET["id"])){
                            if(isset($_SESSION["user"])) {
                                $user = unserialize($_SESSION["user"]);
                                $controller->renderInfo($_GET["id"],$user);
                            }
                        }
                        break;
                    case "statSingle":
                        $controller = new StatController();
                        $data = json_decode(file_get_contents("php://input"));
                        if((new LinkController)->exist($data->id)){
                            $controller->renderSingle($data->id);
                        }
                }
            }
            break;
        case "link":
            $controller = new LinkController();
            if(isset($_GET["sub"])){
                if(isset($_SESSION["user"])){
                    $user = unserialize($_SESSION["user"]);
                    switch($_GET["sub"]){
                        case "admin":
                            if($user->getAdmin() === 1){
                                $controller->renderAll();
                                break;
                            }
                            else{
                                echo json_encode([]);
                            }
                            break;
                        case "guest":
                            $controller->renderConnected($user->getId());
                            break;
                        case "edit":
                            $data = json_decode(file_get_contents("php://input"));
                            $controller->edit($data);
                            break;
                        case "addUsed":
                            $data = json_decode(file_get_contents("php://input"));
                            if(isset($data->id)){
                                $controller->addUsed($data->id);
                            }
                            break;
                        case "add":
                            $data = json_decode(file_get_contents("php://input"));
                            $controller->addLink($data, $user->getId());
                    }
                }

            }

    }
}
else{
    header("Location: index.php?page=home");
}