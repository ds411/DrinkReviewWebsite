<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If s in url, display style page
if(isset($_GET['s'])) {
   include "styleTemplate.php";
}
//If s not in url, display style list
else {
    include "styleList.php";
}
include "template.php";