<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require("category.php");
class Product
{
    public  $name, $price, $prod_image, $availability, $category;
    function __construct( $name, $price, $prod_image, $availability, $category)
    {
        // $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->prod_image = $prod_image;
        $this->availability = $availability;
        $this->category = $category;
        // $this->insert([$name, $price, $prod_image, $availability, $category['id']]);
    }

    public function insert($name, $price, $prod_image, $availability, $category) {
        // var_dump($this->category); 
        $db = dbconnect();
        $sql = 'INSERT INTO product (name, price, image, available, category_id) 
                VALUES ( ?, ?, ?, ?, ?)';
        $db->DML($sql,
         [$name, $price, $prod_image,$availability, $category]);   
        // $db->DML($sql,$data);    
                                   
    
    }

    // function update($id, $newName=$this->name, $price=$this->price, $prod_image=$this->prod_image, $availability=$this->availability)
    // {
    //     $db = dbconnect();
    //     $sql = 'update product set name=?, price=?,prod_id=?, availability where id = ?';
    //     $db->DML($sql,[$newName, $price, $prod_image, $availability, $id]);
    // }

    function update($id, $changed)
    {
        $db = dbconnect();
        
        $sql = 'UPDATE product SET';
        $params = array();
        
        if (isset($changed['name'])) {
            $sql .= ' name = ?,';
            $params[] = $changed['name'];
        }
        
        if (isset($changed['price'])) {
            $sql .= ' price = ?,';
            $params[] = $changed['price'];
        }
        
        if (isset($changed['image'])) {
            $sql .= ' image = ?,';
            $params[] = $changed['image'];
        }
        
        if (isset($changed['available'])) {
            $sql .= ' available = ?,';
            $params[] = $changed['available'];
        }
        
        if (isset($changed['category_id'])) {
            $sql .= ' category_id = ?,';
            $params[] = $changed['category_id'];
        }
        
        // Remove the trailing comma
        $sql = rtrim($sql, ',');
        
        $sql .= ' WHERE id = ?';
        $params[] = $id;
        
        $db->DML($sql, $params);
    }

    function delete($id)
    {
        $db = dbconnect();
        // $sql = 'insert into category (id,name) values('.$id.','.$namem.')';
        $sql = 'delete from product where id = ?';
        $db->DML($sql,[$id]);
    }

    function getName($name)
    {
        $db = dbconnect();
        $result = $db->selectWithCondition('product','name = "'.strtolower($name).'"' );
        return $result;
    }
    function getAll()
    {
        $db = dbconnect();
        $result = $db->select('product');
        return $result;
    }
    

    function getOne($id)
    {
        $db = dbconnect();
        $result = $db->selectWithCondition('product', 'id = '. $id);
        return $result;
    }
	
    
    function getAvailable()
    {
        $db = dbconnect();
        $result = $db->selectWithCondition('product', "available = 'available'");
        return $result;
    }

    function getUnavailable()
    {
        $db = dbconnect();
        $result = $db->selectWithCondition('product', 'available = "unavailable"');
        return $result;
    }

    function getProducts1($offset, $limit, $condition ="")
    {
        $db = dbconnect();
        // $query = "SELECT * FROM products LIMIT ?, ?";
        $result = $db->selectWithLimit('product', $offset, $limit, $condition);
        return $result;
    }

    
}

// echo '<br>-----------------------Product-------<br>';
// $cate1 =  Category::getAllAsObject();
// var_dump($cate1[0]);
// // var_dump($cate1[0]);
// echo '------------<br>' . $cate1[0]['id'];
// $prod1= new Product("Tea33", 10, "tea", "avaliable", (object) $cate1[0]);
// // $prod1= new Product("Tea111", 10, "tea", "avaliable", $cate1);
// $prod1->insert();
// var_dump($prod1->getAll());
// echo '<br>---------------------------<br>';

// print_r($prod1->getAvailable());
// echo '<br>---------------------------<br>';

// print_r($prod1->getUnAvailable());
// echo '<br>----------------update--------------------------<br>';
// $prod1= new Product('Grean Tea', 20, 'pic', 'avaliable', $cate1);
// $prod1->update(11);
// print_r($prod1->getUnAvailable());
?> 