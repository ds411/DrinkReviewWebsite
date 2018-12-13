<?php

$validSession = SessionAuth::isValid();

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
        "<div class='review'><p><a href='profile.php?u=$username' class='feed-user'>$username</a></p>$rating<p class='review-body'>$body</p><p class='review-time'>Posted on $timestamp</p></div>";
}

$reviewForm = "";

if($validSession) {
    $review = ReviewQuery::create()
        ->filterByDrinkId($id)
        ->usePostQuery()
        ->filterByUsername($_SESSION['username'])
        ->endUse()
        ->findOne();
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
			<script>
			$('#submitReview').submit(function(event) {
			    event.preventDefault();
			    $.post({
			        url:$(this).attr('action'),
			        data:$(this).serialize(),
			        success:function(data) {
			            if(data === 'Success.') {
			                $('.drink-review').append(data);
			                $('.drink-make-review').remove();
			            }
			            else {
			                console.log(data);
			            }
			        }
			    });
			});
            </script>
		</div>
EOT;
        $reviewForm = sprintf($reviewForm, $id);
        }
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
		%s
		<hr/>
		<div class='drink-reviews'>
		%s
		</div>
EOT;


$content = sprintf($content, $image, $company, $style, $averageRating, $description, $reviewForm, $reviews);

?>