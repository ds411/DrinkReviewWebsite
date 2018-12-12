<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(isset($_GET['d'])) {
    if(DrinkQuery::create()->filterById($_GET['d'])->exists()) {
        $id = $_GET['d'];
        include "drinkTemplate.php";
    }
    else {
        header("Location: drink.php");
    }
}
else {
    include "drinkList.php";
}


include 'template.php';

?>