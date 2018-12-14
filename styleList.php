<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Style List";

//Styles
$models = StyleQuery::create()
    ->find();

//Style table head
$content = "<table class='table table-striped'><thead><th>Style</th><th>Description</th></thead><tbody>";

//Style table rows
foreach($models as $model) {
    $style = $model->getStyle();
    $description = $model->getDescription();
    $content .= "<tr class='style-types'><td><a href='style.php?s=$style'><h4>$style</h4></td><td><p>$description</p></a></td></tr>";
}

//Complete table
$content .= "</tbody></table>"
?>