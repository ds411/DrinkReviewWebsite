<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//Style
$model = StyleQuery::create()
    ->findByStyle($_GET['s']);

//If style does not exist, redirect to style list
if(!isset($model[0])) {
    header("Location: style.php");
}
else {

    //Valid login
    $validSession = SessionAuth::isValid();

    //Style name
    $style = $model[0]->getStyle();

    $title = $style;

    //Style description
    $description = $model[0]->getDescription();

    //Style drinks
    $drinks = DrinkQuery::create()
        ->filterByStyleName($style)
        ->leftJoin('Drink.Review')
        ->withColumn('AVG(Review.Rating)', 'averageRating')
        ->withColumn('COUNT(Review.Rating)', 'numRatings')
        ->groupBy('Drink.Id')
        ->find();

    //Extra columns for logged in users
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

    //Table head
    $drinksListHeader = "<tr><th>Drink Name <a href='style.php?s=$style&b=dn&o=d'>&#9660</a><a href='style.php?s=$style&b=dn&o=a'>&#9650</a></th><th>Average Rating <a href='style.php?s=$style&b=ar&o=d'>&#9660</a><a href='style.php?s=$style&b=ar&o=a'>&#9650</a></th><th>Ratings <a href='style.php?s=$style&b=r&o=d'>&#9660</a><a href='style.php?s=$style&b=r&o=a'>&#9650</a></th>$validSessionHeaders</tr>";

    //Generate table rows from drink models
    $drinksList = "";
    foreach($drinks as $drink) {
        $drinkId = $drink->getId();
        $drinkName = $drink->getName();
        if(($drinkAvgRating = $drink->getVirtualColumn('averageRating')) === null) {
            $drinkAvgRating = "-.--";
        }
        else $drinkAvgRating = number_format($drinkAvgRating, 2);
        $reviewCount = $drink->getVirtualColumn('numRatings');

        if($validSession) {
            if(!in_array($drinkId, $userReviewsDrinkIds)) $userRating = '<td>-.--</td>';
            else $userRating = "<td>" . $userReviewsDrinkRatings[array_search($drinkId, $userReviewsDrinkIds)] . "</td>";
        }

        $drinksList .= "<tr><td><a href='drink.php?d=$drinkId'>$drinkName</a></td><td>$drinkAvgRating</td><td>$reviewCount</td>$userRating</tr>";
    }

    //html
    $content = "<div><h3>$style</h3><p>$description</p></div><table class='table table-striped'><thead>$drinksListHeader</thead><tbody>$drinksList</tbody></table>";
}

?>