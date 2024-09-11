<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require("dbconnection.php");
 
function dbconnect()
{
    $db = new DBConnection('localhost', 'cafe', 'root', 'mai101214@');
$db->connect('mysql');
    return $db;
}
class Category
{
    public $id, $name;
    // public $getAllAsObject =  $this->getAllAsObject();

    
    function __construct($name)
    {
        // $this->insert($name);
        $this->name = $name;
    }

    function insert($name)
    {
        $db = dbconnect();
        $sql = 'insert into category (name) values(?)';
        // $db->DML($sql,[$this->name]);
        $id = $db->DML($sql,[$name]);
        return $this->id = $id; //the last id
        
    }

    function update($id, $newName)
    {
       $db = dbconnect();
        // $sql = 'insert into category (id,name) values('.$id.','.$namem.')';
        $sql = 'update category set name=? where id = ?';
        $db->DML($sql,[$newName, $id]);
    }

    function delete($id)
    {
       $db = dbconnect();
        // $sql = 'insert into category (id,name) values('.$id.','.$namem.')';
        $sql = 'delete from category where id = ?';
        $db->DML($sql,[$id]);
    }

    function getAll()
    {
        $db = dbconnect();
        $result = $db->select('category');
        return $result;
    }

    public static function getAllAsObject()
    {
        $db = dbconnect();
        $result = $db->select('category');
        foreach($result as $row)
        {
            (object) $row;
        }
        return $result;
    }


    public static function getOneAsObject($name)
    {
       $db = dbconnect();
        $result = $db->selectWithCondition("category", "name = ?" ,[$name]);
        //var_dump($result);
        
        foreach($result as $row)
        {
            (object) $row;
        }
        // print_r($result);
        return $result;
    }

    function getOne($id)
    {
    $db = dbconnect();
        $result = $db->selectWithCondition('category', 'id = '.$id);
        return $result;
    }
    function getName($name)
    {
    $db = dbconnect();
        $result = $db->selectWithCondition('category','name = "'.strtolower($name).'"' );
        return $result;
    }
}


// $cate1 = new Category(3,'Mix_drinks');

// var_dump($cate1->getAll());
// $category = $cate1->getOne(5);
// $cate1->insert();
// $cate1->update($category[0]['id'], 'Hot drinks');
// $cate1->delete($category[0]['id']);
echo '<br>';
// var_dump($category);


// var_dump($cate1->getOne(2));

// foreach($cate1->get() as $row)
// {
//     echo $row;
// }


// var_dump(dbconnect());
// echo 'mona';
?>