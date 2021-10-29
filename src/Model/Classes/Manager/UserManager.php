<?php

namespace Yanntyb\App\Model\Classes\Manager;

use Yanntyb\App\Model\Classes\Entity\User;
use Yanntyb\App\Model\Traits\GlobalManagerTrait;

class UserManager
{
    use GlobalManagerTrait;

    /**
     * @param string $mail
     * @param string $pass
     * @return false|User
     */
    public function newUser(string $mail, string $pass): User|bool
    {
        $conn = $this->db->prepare("INSERT INTO user (mail, pass) VALUES (:mail, :pass)");
        $conn->bindValue(":mail", $this->sanitize($mail));
        $conn->bindValue(":pass", $this->sanitize($pass));
        if($conn->execute()) {
            $user = $this->getSingleEntity($this->db->lastInsertId());
            if ($user) {
                return $user;
            }
            return false;
        }
        return false;
    }
}