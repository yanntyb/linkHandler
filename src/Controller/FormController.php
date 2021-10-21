<?php

namespace Yanntyb\App\Controller;

class FormController
{
    public function checkForm($postParam, array $keys): bool
    {
        $count = 0;
        foreach($keys as $key){
            if(isset($postParam[$key])){
                if($postParam[$key] !== ""){
                    $count++;
                }
            }
        }
        if($count === count($keys)){
            return true;
        }
        return false;
    }
}