<?php
// require("../model/product.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
//require("../model/product.php");
//require(realpath(__DIR__ . '/../model/category.php'));
require(realpath(__DIR__ . '/../model/product.php'));
 
class ProductController
{
    private $cate1;
    private $prod1;

    function __construct()
    {
        // Initialize the Category and Product instances
        $this->cate1 = new Category('mm');
        $this->prod1 = new Product("Tea", 10, "tea", "available", $this->cate1);
    }
    function index()
    {
        $products = $this->prod1->getAll();// all products
        return $products;
    }
    function getNameProduct( $name)
    {
        $product = $this->prod1->getName( $name );
        return $product;
    }
    function getProduct( $id )
    {
        $product = $this->prod1->getOne( $id );
        return $product;
    }

    function getCategory($cat_id)
    {
        $category = $this->cate1->getOne( $cat_id) ;
        return $category;
    }

    function getAllCategories()
    {
        $categories = $this->cate1->getAll();
        return $categories;
    }
    function getAvailables()
    {
        $products = $this->prod1->getAvailable();
        return $products;
    }

    function getUnAvailables()
    {
        $products = $this->prod1->getUnAvailable();
        return $products;
    }

    function delete($id)
    {
        $this->prod1->delete($id);
    }

    function getProducts($offset, $limit, $condition="")
    {
        $productsPerPage = $this->prod1->getProducts1($offset, $limit, $condition);
        return $productsPerPage;
    }
 
    function insertProd($name, $price, $prod_image, $available, $category)
    {
        // echo $prod_image['name'];
        $prod_image1 = 'uploads/' . $prod_image['name'];
        if(move_uploaded_file($prod_image['tmp_name'], $prod_image1))
        {  
            $this->prod1->insert($name, $price, $prod_image['name'], $available, $category);
        }

    }
    function updateProd($id,  $changed)
    { 
        if(isset($changed['image'])) {
           
            $prod_image1 = 'uploads/' . $changed['image']['name'];
            move_uploaded_file($changed['image']['tmp_name'], $prod_image1);
            $changed['image']=  $changed['image']['name'];
        }
        
        $this->prod1->update($id, $changed);
        }
    
    
}
// $p=new ProductController();
// print_r($p->index());
?>