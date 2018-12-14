<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If drink id in url
if(isset($_GET['d'])) {
    //If drink exists, display page
    if(DrinkQuery::create()->filterById($_GET['d'])->exists()) {
        $id = $_GET['d'];
        include "drinkTemplate.php";
    }
    //If drink does not exist, redirect to drink list
    else {
        header("Location: drink.php");
    }
}
//If drink id not in url, display drink list
else {
    include "drinkList.php";
}


include 'template.php';

?>