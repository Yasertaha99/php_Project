<?php
require("../../model/category.php");

class CategoryController 
{

    function isCategoryNameUnique($cate_name){
        $cate = new Category('mm'); 
        $category = $cate->getName($cate_name);
        return  $category ;
    }
    function store($name)
    {
        $cate = new Category('mm'); 
        $category = $cate->insert($name);
    }
}
?>