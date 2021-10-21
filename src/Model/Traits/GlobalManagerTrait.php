<?php

namespace Yanntyb\App\Model\Traits;

use Yanntyb\App\Model\Classes\DB;
use PDO;
use PDOException;

trait GlobalManagerTrait
{

    private ?PDO $db;
    private array $fk;

    /**
     * ArticleManager constructor.
     */
    public function __construct(){
        $this->db = DB::getInstance();
        $this->fk = [];
    }

    public function sanitize(string $data): string
    {
        return trim(stripslashes(htmlspecialchars(addslashes($data))));
    }

    /**
     * Get single entity based on his colonne name and value
     * Default is id = :id
     * @param string $val
     * @param string $col
     */
    public function getSingleEntity(string $val, string $col = "id")
    {
        $conn = $this->db->prepare("SELECT * FROM " . strtolower($this->getClassName()) . " WHERE " . $this->sanitize($col) . " = :id");
        $conn->bindValue(":id", $this->sanitize($val));
        if($conn->execute()){
            $results = $conn->fetch();
            if($results){
                //Créée l'entité qui porte le nom $name
                $qualifiedName = "Yanntyb\App\Model\Classes\Entity\\" . $this->getClassName();
                $obj = new $qualifiedName;
                //Parcoure toute les valeurs fetch
                return $this->getObj($results, $obj);
            }
            return false;
        }
        return false;
    }

    /**
     * Return an array of objected base on database's data
     * If $col and $colVal specified , only query a certain colonne of the database
     * @param string $col
     * @param string $colVal
     * @return array
     */
    public function getAllEntity(string $col = null, string $colVal = null): array
    {
        if($col && $colVal){
            $conn = $this->db->prepare("SELECT * FROM " . strtolower($this->getClassName()) . " WHERE " . $this->sanitize($col) . " = " . $this->sanitize($colVal));
        }
        else{
            $conn = $this->db->prepare("SELECT * FROM " . strtolower($this->getClassName()) );
        }
        $return = [];
        if($conn->execute()){
            $results = $conn->fetchAll();
            foreach($results as $result){
                $obj = $this->createObj($this->getClassName(),true);
                $obj = $this->getObj($result, $obj);
                $return[] = $obj;
            }
        }
        return $return;
    }

    public function getClassName(){
        if(str_contains(get_class($this), "\\")){
            $fullName = str_split(explode("\\",get_class($this))[5]);
        }
        else{
            $fullName = str_split(get_class($this));
        }

        $countUpper = 0;
        foreach($fullName as $index => $letter){
            if(ctype_upper($letter)){
                $countUpper++;
            }
            if($countUpper === 2){
                return substr(implode($fullName),0,$index);
            }
        }
    }

    public function checkIfTableExist(string $name): bool
    {
        //Si $name n'a pas encore été check alors on test si la table existe
        if(!in_array($name,$this->fk)){
            try{
                $conn = $this->db->prepare("SELECT 1 FROM " . explode("_",$name)[0]);
                if($conn->execute()){;
                    $this->fk[] = $name;
                    return true;
                }
                else{
                    return false;
                }
            }
            catch (PDOException $e){
                return false;
            }
        }
        else{
            return true;
        }
    }

    //Si $first === true c'est que c'est la premiere iteration , le manager correspond donc et on a pas besoin den créé un nouveau
    public function createObj(string $name, bool $first, $id = 0){
        if($first){
            $qualifiedName = "Yanntyb\\App\\Model\\Classes\\Entity\\" .$name;
            return new $qualifiedName;
        }
        else{
            if($this->checkIfTableExist($name)){
                $subObjName = ucfirst(explode("_",$name)[0]);
                $managerName = $subObjName . "Manager";
                $qualifiedName = "Yanntyb\\App\\Model\\Classes\\Manager\\" . $managerName;
                $manager = new $qualifiedName;
                return $manager->getSingleEntity($id);
            }
        }
    }

    /**
     * @param $results
     * @param $obj
     */
    public function getObj($results, $obj)
    {
        foreach ($results as $col => $value) {
            $obj2 = $this->createObj($col, false, $value);
            $methode = "set" . ucfirst(explode("_", $col)[0]);
            if (method_exists($obj, $methode)) {
                if (is_null($obj2)) {
                    $obj = $obj->$methode($value);
                } else {
                    $obj = $obj->$methode($obj2);
                }
            }
        }
        return $obj;
    }
}