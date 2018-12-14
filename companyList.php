<?php

$title = "Company List";

//Get page offset
if(isset($_GET['page'])) $offset = $_GET['page'] * 20;
else $offset = 0;

//Get sort order
if(isset($_GET['o']) && $_GET['o'] === 'd') $order = 'DESC';
else $order = 'ASC';

//Get sort column
if(isset($_GET['b']) && $_GET['b'] === 'nd') $col = 'drinkCount';
else $col = 'name';

//Get companies
$models = CompanyQuery::create()
    ->leftJoin('Company.Drink')
    ->withColumn('COUNT(Drink.Id)', 'drinkCount')
    ->groupBy('Company.Id')
    ->limit(20)
    ->offset($offset)
    ->orderBy($col, $order)
    ->find();

//Create table header
$content = "<table class='table table-striped'><thead><tr><th>Company Name <a href='company.php?b=cn&o=d'>&#9660</a><a href='company.php?b=cn&o=a'>&#9650</a></th><th>Number of Drinks <a href='company.php?b=nd&o=d'>&#9660</a><a href='company.php?b=nd&o=a'>&#9650</a></th></tr></thead><tbody>";

//Create table contents from company models
foreach($models as $model) {
    $id = $model->getId();
    $name = $model->getName();
    $drinkCount = $model->getVirtualColumn('drinkCount');
    $content .= "<tr><td><a href='company.php?c=$id'>$name</a></td><td>$drinkCount</td></tr>";
}

//Finish creating table
$content .= "</tbody></table>";

?>