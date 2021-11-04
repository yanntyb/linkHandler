<?php

namespace Yanntyb\App\Model\Classes\Manager;

use Yanntyb\App\Model\Classes\Entity\link;
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
        $link = $this->getSingleEntity($id);
        $conn = $this->db->prepare("DELETE FROM link WHERE id = :id");
        $conn->bindValue(":id",$id);
        $conn->execute();
        if (file_exists($_SERVER["DOCUMENT_ROOT"] ."/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png")) {
            unlink($_SERVER["DOCUMENT_ROOT"] ."/assets/thumb/{$link->getUser()->getId()}-{$link->getId()}.png");
        }
    }

    public function edit($values){
        dump($values);
        $conn = $this->db->prepare("UPDATE link SET href = :href, title = :title, target = :target, name = :name WHERE id = :id");
        foreach($values as $key => $value){
            $conn->bindValue(":{$key}", $value);
        }
        $conn->execute();
    }

    public function addUsed(Link $link){
        $conn = $this->db->prepare("UPDATE link SET used = :used WHERE id = :id");
        $conn->bindValue(":id",$link->getId());
        $conn->bindValue(":used", $link->getUsed() + 1);
        $conn->execute();
    }
}