<?php

namespace Yanntyb\App\Model\Classes\Manager;

use Yanntyb\App\Model\Traits\GlobalManagerTrait;

class LinkManager
{
    use GlobalManagerTrait;

    public function newLink(array $values){
        $conn = $this->db->prepare("INSERT INTO link (href, title, target, name) VALUES (:href, :title, :target, :name)");
        foreach($values as $key => $value){
            $conn->bindValue(":{$key}", $value);
        }
        $conn->execute();
    }
}