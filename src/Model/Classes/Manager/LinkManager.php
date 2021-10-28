<?php

namespace Yanntyb\App\Model\Classes\Manager;

use Yanntyb\App\Model\Traits\GlobalManagerTrait;

class LinkManager
{
    use GlobalManagerTrait;

    public function newLink(array $values, int $user_id){
        $conn = $this->db->prepare("INSERT INTO link (href, title, target, name, user_fk) VALUES (:href, :title, :target, :name, :user)");
        foreach($values as $key => $value){
            $conn->bindValue(":{$key}", $value);
        }
        $conn->bindValue(":user", $user_id);
        $conn->execute();
    }

    public function delete(int $id){
        $conn = $this->db->prepare("DELETE FROM link WHERE id = :id");
        $conn->bindValue(":id",$id);
        $conn->execute();
    }

    public function edit(array $values){
        $conn = $this->db->prepare("UPDATE link SET href = :href, title = :title, target = :target, name = :name WHERE id = :id");
        foreach($values as $key => $value){
            $conn->bindValue(":{$key}", $value);
        }
        $conn->execute();
    }
}