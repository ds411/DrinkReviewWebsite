<?php

//Get page offset
if(isset($_GET['page'])) $offset = $_GET['page'] * 20;
else $offset = 0;

//Get sort order
if(isset($_GET['o']) && $_GET['o'] === 'd') $order = 'DESC';
else $order = 'ASC';

//Valid session
$validSession = SessionAuth::isValid();

//Get sort column
$sortBy = isset($_GET['b']);
if($sortBy && $_GET['b'] === 'nr') $col = 'reviewCount';
else if($sortBy && $_GET['b'] === 'ar') $col = 'averageRating';
else if($sortBy && $_GET['b'] === 's') $col = 'style_name';
else $col = 'name';

//Get company
$companyModel = CompanyQuery::create()
    ->findOneById($id);

//Company name
$company = $companyModel->getName();

//Company picture
$image = $companyModel->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

//Company locastion
$location = $companyModel->getLocation();

//Company description
$description = $companyModel->getDescription();

$title = $company;

//Get drinks
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

//Columns for logged in users
if($validSession) {
    $validSessionHeaders = "<th>Your Rating</th>";
    $userReviews = ReviewQuery::create()
        ->usePostQuery()
        ->filterByUsername($_SESSION['username'])
        ->endUse()
        ->find();
    $userReviewsDrinkIds = $userReviews->getColumnValues('drinkid');
    $userReviewsDrinkRatings = $userReviews->getColumnValues('rating');
    $loggedInHeaders = "<th>Your Rating <a href='company.php?c=$id&b=yr&o=d'>&#9660</a><a href='company.php?c=$id&b=yr&o=a'>&#9650</a></th>";
}

//Columns for all users
$drinksListHeader = "<tr><th>Drink Name <a href='company.php?c=$id&b=dn&o=d'>&#9660</a><a href='company.php?c=$id&b=dn&o=a'>&#9650</a></th><th>Style <a href='company.php?c=$id&b=s&o=d'>&#9660</a><a href='company.php?c=$id&b=s&o=a'>&#9650</a></th><th>Average Rating <a href='company.php?c=$id&b=ar&o=d'>&#9660</a><a href='company.php?c=$id&b=ar&o=a'>&#9650</a></th><th>Ratings <a href='company.php?c=$id&b=r&o=d'>&#9660</a><a href='company.php?c=$id&b=r&o=a'>&#9650</a></th>$validSessionHeaders</tr>";
$drinksList = "";

//Generate table rows from drink models
foreach($drinks as $drink) {
    $drinkId = $drink->getId();
    $style = $drink->getStyleName();
    $drinkName = $drink->getName();
    if(($drinkAvgRating = $drink->getVirtualColumn('averageRating')) === null) {
        $drinkAvgRating = "-.--";
    }
    else $drinkAvgRating = number_format($drinkAvgRating, 2);
    $reviewCount = $drink->getVirtualColumn('numRatings');

    //If user is logged in, include their ratings
    if($validSession) {
        if(!in_array($drinkId, $userReviewsDrinkIds)) $userRating = '<td>-.--</td>';
        else $userRating = "<td>" . $userReviewsDrinkRatings[array_search($drinkId, $userReviewsDrinkIds)] . "</td>";
    }

    $drinksList .= "<tr><td><a href='drink.php?d=$drinkId'>$drinkName</a></td><td>$style</td><td>$drinkAvgRating</td><td>$reviewCount</td>$userRating</tr>";
}

//Finish creating table
$drinkTable = "<table class='table'><thead>$drinksListHeader</thead><tbody>$drinksList</tbody></table>";

//html
$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2'>
				    <img src="%s" height="150" width="150" />
				</div>
				<div class='col-md-6'>
				    <p>Name: <b>%s</b></p>
                    <p>Location: <b>%s</b></p>
				</div>
				<div class='col-md-4'>
                    <p>Description:</p>
				    <p class='dc-desc'>%s</p>
				</div>
			</div>
		</div>
		<div class='company-other'>
		%s
		</div>
EOT;

$content = sprintf($content, $image, $company, $location, $description, $drinkTable);

?>