<?php

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

$companyModel = CompanyQuery::create()
    ->findOneById($id);

$company = $companyModel->getName();

$image = $companyModel->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

$location = $companyModel->getLocation();
$description = $companyModel->getDescription();

$title = $company;

$drinks = DrinkQuery::create()
    ->filterByCompanyId($id)
    ->leftJoin('Drink.Review')
    ->withColumn('AVG(Review.Rating)', 'averageRating')
    ->withColumn('COUNT(Review.Rating)', 'numRatings')
    ->groupBy('Drink.Id')
    ->limit(20)
    ->offset($offset)
    ->orderBy($col, $order)
    ->find();


$validSession = SessionAuth::isValid();


if($validSession) {
    $validSessionHeaders = "<th>Your Rating</th>";
    $userReviews = ReviewQuery::create()
        ->usePostQuery()
        ->filterByUsername($_SESSION['username'])
        ->endUse()
        ->find();
    $userReviewsDrinkIds = $userReviews->getColumnValues('drink_id');
}

if($validSession) {
    $loggedInHeaders = "<th>Your Rating <a href='company.php?c=$id&b=yr&o=d'>&#9660</a><a href='company.php?c=$id&b=yr&o=a'>&#9650</a></th>";
}

$drinksListHeader = "<tr><th>Drink Name <a href='company.php?c=$id&b=dn&o=d'>&#9660</a><a href='company.php?c=$id&b=dn&o=a'>&#9650</a></th><th>Style <a href='company.php?c=$id&b=s&o=d'>&#9660</a><a href='company.php?c=$id&b=s&o=a'>&#9650</a></th><th>Average Rating <a href='company.php?c=$id&b=ar&o=d'>&#9660</a><a href='company.php?c=$id&b=ar&o=a'>&#9650</a></th><th>Ratings <a href='company.php?c=$id&b=r&o=d'>&#9660</a><a href='company.php?c=$id&b=r&o=a'>&#9650</a></th>$validSessionHeaders</tr>";
$drinksList = "";

foreach($drinks as $drink) {
    $drinkId = $drink->getId();
    $style = $drink->getStyleName();
    $drinkName = $drink->getName();
    if(($drinkAvgRating = $drink->getVirtualColumn('averageRating')) === null) {
        $drinkAvgRating = "-.--";
    }
    $reviewCount = $drink->getVirtualColumn('numRatings');

    if($validSession) {
        if(!isset($userReviewsDrinkIds[$drinkId])) $userRating = '<td>-.--</td>';
        else $userRating = $userReviewsDrinkIds[$drinkId];
    }
    $userRating = "<td>-.--</td>";

    if($reviewCount === 0) {
        $drinkAvgRating = "-.--";
    }

    $drinksList .= "<tr><td><a href='drink.php?d=$drinkId'>$drinkName</a></td><td>$style</td><td>$drinkAvgRating</td><td>$reviewCount</td>$userRating</tr>";
}

$drinkTable = "<table><thead>$drinksListHeader</thead><tbody>$drinksList</tbody></table>";

$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2' style='background-color:red;'>
				    <img src="%s" height="150" width="150" />
				</div>
				<div class='col-md-6' style='background-color:blue;'>
				%s, %s
				</div>
				<div class='col-md-4' style='background-color:red;'>
				%s
				</div>
			</div>
		</div>
		<div class='company-other'>
		%s
		</div>
EOT;

$content = sprintf($content, $image, $company, $location, $description, $drinkTable);

?>