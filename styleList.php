<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Style List";

$models = StyleQuery::create()
    ->find();

$content = "<table class='table table-striped'><thead><th>Style</th><th>Description</th></thead><tbody>";

foreach($models as $model) {
    $style = $model->getStyle();
    $description = $model->getDescription();
    $content .= "<tr class='style-types'><td><a href='style.php?s=$style'><h4>$style</h4></td><td><p>$description</p></a></td></tr>";
}
$content .= "</tbody></table>"
?>