<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//Company controller

//If company id in url
if(isset($_GET['c'])) {
    //If company exists, display company page
    if(CompanyQuery::create()->filterById($_GET['c'])->exists()) {
        $id = $_GET['c'];
        include "companyTemplate.php";
    }
    //If company does not exist, redirect to company list
    else {
        header("Location: company.php");
    }
}
//If company id not in url, display company list
else {
    include "companyList.php";
}


include 'template.php';

?>