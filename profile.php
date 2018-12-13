<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(isset($_GET['u'])) {
    if(UserQuery::create()->filterByUsername($_GET['u'])->exists()) {
        $username = $_GET['u'];
    }
    else {
        header("Location: profile.php");
    }
}
else if(SessionAuth::isValid()) {
    $username = $_SESSION['username'];
}
else {
    header("Location: index.php");
}

$title = "$username's Profile";

$user = UserQuery::create()
    ->findOneByUsername($username);

$image = $user->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

$postModels = PostQuery::create()
    ->filterByUsername($username)
    ->orderByCreationtime('DESC')
    ->limit(20)
    ->offset(0)
    ->find();
$posts = "";
foreach($postModels as $post) {
    $timestamp = $post->getCreationtime()->format('Y-m-d H:i:s');
    $body = $post->getBody();
    $id = "";
    $drink = "";
    $rating = "";
    if(($review = $post->getReview()) !== null) {
        $id = $review->getDrinkId();
        $drink = " &#x3e; <a href='drink.php?d=$id'>" . $review->getDrink()->getName() . "</a>";
        $rating = "<p class='rating'>" . $review->getRating() . "</p>";
    }
    $posts .=
        "<div class='feed-post'><p><a href='profile.php?u=$username' class='feed-user'>$username</a>$drink</p>$rating<p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
}

$friendModels = $user->getFriendsRelatedByUsername();
$friends = "";
foreach($friendModels as $friend) {
    $name = $friend->getUsername();
    $friends .= "<div><a href='profile.php?u=$name'>$name</a></div>";
}

if(SessionAuth::isValid() && $_SESSION['username'] === $username) {
    $imageUpload = <<<EOT
            <div class='imgUpload'>
				<label for="file-upload" class="custom-file-upload">
				    <i class="fas fa-upload"></i> Change Image
				    <form id="imgUploadForm">
				        <input id="profileImg-upload" name='image' type="file"/>
				    </form>
				</label>
			</div>
			<script>
            $('#imgUploadForm').change(function(event) {
                $.post({
                    url:'profileImageUpload.php',
                    data:new FormData(this),
                    processData:false,
                    contentType:false,
                    success:function(data) {
                        if(data.indexOf('<') !== -1) {
                            $('#profileImg').replaceWith(data);
                        }
                        else {
                            console.log(data);
                        }
                    }
                });
            });
            </script>
EOT;
}
else {
    $imageUpload = "";
}


$content = <<<EOT
	<div class='row profile-body'>
		<div class='col-md-2 profile-info'>
			<div class='profile-img'>
				<img id="profileImg" src="%s" height="150" width="150" />
			</div>
			%s
		</div>
		<div class='col-md-8 profile-post'>
			%s
		</div>
		<div class='col-md-2 friend-info'>
			%s
		</div>
	</div>
EOT;

$content = sprintf($content, $image, $imageUpload, $posts, $friends);

include "template.php";
?>