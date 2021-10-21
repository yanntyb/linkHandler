<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Manager\UserManager;

class UserController
{
    public function check_login($var, $keys){
        $manager = new UserManager;
        var_dump($var);
        $user = $manager->getSingleEntity($var["mail"],"mail");
        if($user->getPass() === $var["pass"]){
            return $user;
        }
        return false;
    }
}