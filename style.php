<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(isset($_GET['s'])) {
   include "styleTemplate.php";
}
else {
    include "styleList.php";
}
include "template.php";