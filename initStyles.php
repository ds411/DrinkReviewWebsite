<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(!isset(StyleQuery::create()->filterByStyle('IPA')->find()[0])) {
    $s = new Style();
    $s->setStyle('IPA')->setDescription('Description goes here')->save();
}


?>