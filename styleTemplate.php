<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$model = StyleQuery::create()
    ->findByStyle($_GET['s']);



if(!isset($model[0])) {
    header("Location: style.php");
}
else {
    $style = $model[0]->getStyle();
    $description = $model[0]->getDescription();
    $drinks = $model[0]->getDrinks();
    $drinks = DrinkQuery::create()
        ->filterByStyleName($style)
        ->joinReview()
        ->withColumn('AVG(Review.Rating)', 'avgRating')
        ->withColumn('COUNT(Review.Rating)', 'numRatings');



    if(isset($_SESSION['username'])) {
        $loggedInHeaders = "<th>Your Rating <a href='style.php?s=$style&b=yr&o=d'>&#9660</a><a href='style.php?s=$style&b=yr&o=a'>&#9650</a></th><th>Wishlist</th>";
        $drinks
            ->joinWishlist()->where('Wishlist.Username = ?', $_SESSION['username'])
            ->withColumn('Wishlist.Username', 'wishlist');
    }

    $drinksListHeader = "<tr><th>Drink Name <a href='style.php?s=$style&b=dn&o=d'>&#9660</a><a href='style.php?s=$style&b=dn&o=a'>&#9650</a></th><th>Average Rating <a href='style.php?s=$style&b=ar&o=d'>&#9660</a><a href='style.php?s=$style&b=ar&o=a'>&#9650</a></th><th>Ratings <a href='style.php?s=$style&b=r&o=d'>&#9660</a><a href='style.php?s=$style&b=r&o=a'>&#9650</a></th>$loggedInHeaders</tr>";
    $drinksList = "";

    $drinks = $drinks->find();

    foreach($drinks as $drink) {
        $drinkId = $drink->getId();
        if($drinkId === null) break;
        $drinkName = $drink->getName();
        if(($drinkAvgRating = $drink->getVirtualColumn('avgRating')) === null) {
            $drinkAvgRating = "-.--";
        }
        $reviewCount = $drink->getVirtualColumn('numRatings');


        if(isset($_SESSION['username'])) {
            if ($drink->getVirtualColumn('wishlist') === $_SESSION['username']) {
                $wishlist = "<td>+</td>";
            } else {
                $wishlist = "<td>-</td>";
            }
        }
        else {
            $wishlist = "";
        }

        //$drinkTotalRating = 0;
        //$reviewCount = 0;
        $userRating = "<td>-.--</td>";
        /*

        $count = 0;
        foreach($drink->getReviews() as $review) {
            $rating = $review->getRating();
            $drinkTotalRating += $rating;
            $reviewCount++;
            if(isset($_SESSION['username']) && ($review->getPost()->getUsername() === $_SESSION['username'])) {
                $userRating = "<td>" . $rating . "</td>";
            }
        }*/
        if($reviewCount === 0) {
            $drinkAvgRating = "-.--";
        }

        $drinksList .= "<tr><td><a href='drink.php?d=$drinkId'>$drinkName</a></td><td>$drinkAvgRating</td><td>$reviewCount</td>$userRating$wishlist</tr>";
        //print_r($drink);
    }

    $content = "<div><h3>$style</h3><p>$description</p></div><table><thead>$drinksListHeader</thead><tbody>$drinksList</tbody></table>";

    //print_r($drinks->getData());
}

?>