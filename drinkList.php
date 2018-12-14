<?php

$title = "Drink List";

if(isset($_GET['page'])) $offset = $_GET['page'] * 20;
else $offset = 0;

if(isset($_GET['o']) && $_GET['o'] === 'd') $order = 'DESC';
else $order = 'ASC';

$validSession = SessionAuth::isValid();
$sortBy = isset($_GET['b']);
if($sortBy && $_GET['b'] === 'nr') $col = 'reviewCount';
else if($sortBy && $_GET['b'] === 'ar') $col = 'averageRating';
else if($sortBy && $_GET['b'] === 's') $col = 'style_name';
else $col = 'name';

$models = DrinkQuery::create()
    ->leftJoin('Drink.Review')
    ->withColumn('COUNT(Review.PostId)', 'reviewCount')
    ->withColumn('AVG(Review.Rating)', 'averageRating')
    ->groupBy('Id')
    ->limit(20)
    ->offset($offset)
    ->orderBy($col, $order)
    ->find();

if($validSession) {
    $validSessionHeaders = "<th>Your Rating</th>";
    $userReviews = ReviewQuery::create()
        ->usePostQuery()
        ->filterByUsername($_SESSION['username'])
        ->endUse()
        ->find();
    $userReviewsDrinkIds = $userReviews->getColumnValues('drinkid');
    $userReviewsDrinkRatings = $userReviews->getColumnValues('rating');
}
else $validSessionHeaders = "";
$content = "<table class='table table-striped'><thead><tr><th>Drink Name <a href='drink.php?b=dn&o=d'>&#9660</a><a href='drink.php?b=dn&o=a'>&#9650</a></th></th><th>Style <a href='drink.php?b=s&o=d'>&#9660</a><a href='drink.php?b=s&o=a'>&#9650</a></th><th>Average Rating <a href='drink.php?b=ar&o=d'>&#9660</a><a href='drink.php?b=ar&o=a'>&#9650</a></th></th><th>Number of Reviews <a href='drink.php?b=nr&o=d'>&#9660</a><a href='drink.php?b=nr&o=a'>&#9650</a></th>$validSessionHeaders</tr></thead><tbody>";

foreach($models as $model) {
    $id = $model->getId();
    $name = $model->getName();
    $style = $model->getStyleName();
    $averageRating = !($model->getVirtualColumn('averageRating')) ? '-.--' : number_format($model->getVirtualColumn('averageRating'), 2);
    $reviewCount = $model->getVirtualColumn('reviewCount');
    $userRating = "";
    if($validSession) {
        if(!in_array($id, $userReviewsDrinkIds)) $userRating = '<td>-.--</td>';
        else $userRating = "<td>" . $userReviewsDrinkRatings[array_search($id, $userReviewsDrinkIds)] . "</td>";
    }
    $content .= "<tr><td><a href='drink.php?d=$id'>$name</a></td><td>$style</td><td>$averageRating</td><td>$reviewCount</td>$userRating</tr>";
}

$content .= "</tbody></table>";

?>