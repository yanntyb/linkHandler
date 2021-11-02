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

    public function changePass(User $user, string $newPass){
        $conn = $this->db->prepare("UPDATE user SET pass = :pass WHERE id = :id");
        $conn->bindValue(":pass", $this->sanitize($newPass));
        $conn->bindValue(":id", $user->getId());
        $conn->execute();
        return $this->getSingleEntity($user->getId());
    }

    public function changeApiKey(User $user, string $apiKey){
        $conn = $this->db->prepare("UPDATE user SET apikey = :key WHERE id = :id");
        $conn->bindValue(":key", $this->sanitize($apiKey));
        $conn->bindValue(":id", $user->getId());
        $conn->execute();
        return $this->getSingleEntity($user->getId());
    }

    public function changeApiSecret(User $user, string $apiSecret){
        $conn = $this->db->prepare("UPDATE user SET apisecret = :secret WHERE id = :id");
        $conn->bindValue(":secret", $this->sanitize($apiSecret));
        $conn->bindValue(":id", $user->getId());
        $conn->execute();
        return $this->getSingleEntity($user->getId());
    }
}