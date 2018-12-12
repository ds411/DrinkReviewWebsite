<?php

$model = DrinkQuery::create()
    ->leftJoin('Drink.Review')
    ->withColumn('AVG(Review.Rating)', 'averageRating')
    ->findOneById($id);

$drink = $model->getName();

$image = $model->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

$company = $model->getCompany()->getName();
$style = $model->getStyleName();
$description = $model->getDescription();
$averageRating = $model->getVirtualColumn('averageRating');

$title = $drink;

if(isset($_GET['page'])) $offset = 20 * $_GET['page'];
else $offset = 0;

$reviewModels = ReviewQuery::create()
    ->filterByDrinkId($id)
    ->limit(20)
    ->offset($offset)
    ->find();

$reviews = "";
foreach($reviewModels as $review) {
    $post = $review->getPost();
    $username = $post->getUsername();
    $timestamp = $post->getCreationtime()->format('Y-m-d H:i:s');
    $body = $post->getBody();
    $rating = $review->getRating();
    $reviews .=
        "<div class='review'><p><a href='profile.php?u=$username' class='feed-user'>$username</a></p>$rating<p class='review-time'>Posted on $timestamp</p><p class='review-body'>$body</p></div>";
}

$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2' style='background-color:red;'>
				    <img src="%s" height="150" width="150" />
				</div>
				<div class='col-md-6' style='background-color:blue;'>
				%s, %s, %s
				</div>
				<div class='col-md-4' style='background-color:red;'>
				%s
				</div>
			</div>
		</div>
		<div class='drink-reviews'>
		%s
		</div>
EOT;


$content = sprintf($content, $image, $company, $style, $averageRating, $description, $reviews);

?>