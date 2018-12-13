<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Style List";

$models = StyleQuery::create()
    ->find();

$content = "";

foreach($models as $model) {
    $style = $model->getStyle();
    $description = $model->getDescription();
    $content .= "<div><a href='style.php?s=$style'><div><h3>$style</h3><p>$description</p></div></a></div>";
}

?>