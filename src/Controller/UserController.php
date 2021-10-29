<?php

namespace Yanntyb\App\Controller;

use Yanntyb\App\Model\Classes\Entity\User;
use Yanntyb\App\Model\Classes\Manager\UserManager;

class UserController
{
    private UserManager $manager;

    public function __construct(){
        $this->manager = new UserManager;
    }

    public function check_login($var){
        $user = $this->manager->getSingleEntity($var["mail"],"mail");
        if($user->getPass() === $var["pass"]){
            return $user;
        }
        return false;
    }

    /**
     * @param array $values
     * @return User|bool
     */
    public function register(array $values): User|bool
    {
        if(!$this->manager->getSingleEntity($values["mail"], "mail")) {
            if ($values["pass"] === $values["re_pass"]) {
                $user = $this->manager->newUser($values["mail"], $values["pass"]);
                if ($user) {
                    return $user;
                }
                return false;
            }
        }
        return false;
    }
}