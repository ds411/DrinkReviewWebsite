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
				<div class='col-md-2'>
				    <img src="%s" height="150" width="150" />
				</div>
				<div class='col-md-6'>
				%s, %s, %s
				</div>
				<div class='col-md-4'>
				%s
				</div>
			</div>
		</div>

		<!-- ********* only show if they haven't reviewed drink yet ***** -->
		<div class='drink-make-review'>
			<h5>Write a Review</h5>
			<form method='POST' action='#'>
				<label>Score</label>
				<select name='userScore' id='userScore' class='form-control' required>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option selected>5</option>
				</select>
				<label>Review</label>
				<textarea class='form-control'></textarea>
			</form>
		</div>
		<hr/>
		<div class='drink-reviews'>
		%s
		</div>
EOT;


$content = sprintf($content, $image, $company, $style, $averageRating, $description, $reviews);

?>