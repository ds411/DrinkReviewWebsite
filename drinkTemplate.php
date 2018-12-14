<?php

//Valid session
$validSession = SessionAuth::isValid();

//Drink model from id
$model = DrinkQuery::create()
    ->leftJoin('Drink.Review')
    ->withColumn('AVG(Review.Rating)', 'averageRating')
    ->findOneById($id);

//Drink name
$drink = $model->getName();

//Drink picture
$image = $model->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

//Drink company
$company = $model->getCompany()->getName();
//Drink company id
$company_id = $model->getCompanyId();
//Drink company in anchor tag
$company = "<a href='company.php?c=$company_id'>$company</a>";

//Drink style
$style = $model->getStyleName();
//Drink style in anchor tag
$style = "<a href='style.php?s=$style'>$style</a>";

//Drink description
$description = $model->getDescription();

//Drink average rating
$averageRating = $model->getVirtualColumn('averageRating');

$title = $drink;

//Page offset
if(isset($_GET['page'])) $offset = 20 * $_GET['page'];
else $offset = 0;

//Drink reviews
$reviewModels = ReviewQuery::create()
    ->filterByDrinkId($id)
    ->orderByPostId('DESC')
    ->limit(20)
    ->offset($offset)
    ->find();

//Create reviews section from review models
$reviews = "<div class='review-container'>
				<h5>Reviews</h5>
			</div>";
foreach($reviewModels as $review) {
    $post = $review->getPost();
    $username = $post->getUsername();
    $timestamp = $post->getCreationtime()->format('Y-m-d H:i:s');
    $body = $post->getBody();
    $rating = $review->getRating();
    $reviews .=
        "<div class='review-post' style='margin-bottom:1%;'><p><a href='profile.php?u=$username' class='feed-user'>$username</a></p><p>Rating: $rating / 5</p><p class='feed-body'>$body</p><hr/><p class='feed-time'>Posted on $timestamp</p></div>";
}

//Review form is empty by default
$reviewForm = "";

//If valid login
if($validSession) {

    //Check if user has reviewed the drink
    $review = ReviewQuery::create()
        ->filterByDrinkId($id)
        ->usePostQuery()
        ->filterByUsername($_SESSION['username'])
        ->endUse()
        ->findOne();

    //If the user has not reviewed the drink, include the review form
    if($review === null) {
        $reviewForm = <<<EOT
        <div class='drink-make-review'>
			<h5>Write a Review</h5>
			<form method='POST' id='submitReview' action='submitReview.php?d=%d'>
				<label>Score</label>
				<select name='rating' id='rating' class='form-control' required>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5" selected>5</option>
				</select>
				<label>Review</label>
				<textarea name='body' class='form-control' required></textarea>
				</br>
				<input type="submit" />
			</form>
		</div>
EOT;
        $reviewForm = sprintf($reviewForm, $id);
        }
}

//html
$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2'>
				    <img src="%s" height="150" width="150" />
				</div>
				<div class='col-md-6'>
					<p>Company: <b>%s</b></p>
					<p>Style: <b>%s</b></p>
					<p>Average Rating: <b>%s</b></p>
				</div>
				<div class='col-md-4'>
					<p>Description:</p>
					<p class='dc-desc'>%s</p>
				</div>
			</div>
		</div>
		%s
		<hr/>
		<div id='drink-reviews'>
		%s
		</div>
		<script>
		$('#submitReview').submit(function(event) {
		    event.preventDefault();
		    $.post({
		        url:$(this).attr('action'),
		        data:$(this).serialize(),
		        success:function(data) {
		            if(data.indexOf('<') !== -1) {
		                $('#drink-reviews').prepend(data);
		                $('.drink-make-review').remove();
		            }
		            else {
		                console.log(data);
		            }
		        }
		    });
		});
        </script>
EOT;


$content = sprintf($content, $image, $company, $style, $averageRating, $description, $reviewForm, $reviews);

?>