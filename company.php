<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(isset($_GET['c'])) {
    if(CompanyQuery::create()->filterById($_GET['c'])->exists()) {
        $id = $_GET['c'];
        include "companyTemplate.php";
    }
    else {
        header("Location: company.php");
    }
}
else {
    include "companyList.php";
}


include 'template.php';

?>