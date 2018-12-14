<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If u in url
if(isset($_GET['u'])) {
    //If user exists with username u, display their page
    if(UserQuery::create()->filterByUsername($_GET['u'])->exists()) {
        $username = $_GET['u'];
    }
    //Otherwise, redirect to profile.php
    else {
        header("Location: profile.php");
    }
}
//If u not in url and valid login, display logged in user's profile
else if(SessionAuth::isValid()) {
    $username = $_SESSION['username'];
}
//If no u in url and user not logged in, redirect to index
else {
    header("Location: index.php");
}

$title = "$username's Profile";

//User
$user = UserQuery::create()
    ->findOneByUsername($username);

//User picture
$image = $user->getPicture();
if($image === null) {
    $image = "noimageavailable.png";
}
else {
    $image = "images/" . $image;
}

//User posts
$postModels = PostQuery::create()
    ->filterByUsername($username)
    ->orderByCreationtime('DESC')
    ->limit(20)
    ->offset(0)
    ->find();

//Generate html from post models
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
        $rating = "<p class='rating'>Rating: <b>" . $review->getRating() . " / 5</b></p>";
    }
    $posts .=
        "<div class='feed-post'><p><a href='profile.php?u=$username' class='feed-user'>$username</a>$drink</p>$rating<p class='feed-body'>$body</p><hr/><p class='feed-time'>Posted on $timestamp</p></div>";
}

//User friends
$friendModels = $user->getFriendsRelatedByUsername();

//Generate html from friends
$friends = "";
foreach($friendModels as $friend) {
    $name = $friend->getFriendUsername();
    $friends .= "<div><a href='profile.php?u=$name' class='list-group-item' >$name</a></div>";
}

//Follow button does not exist by default
$followButton = "";
//Image upload button does not exist by default
$imageUpload = "";

//If valid session and profile is logged in user's profile,
// include profile image upload form
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
//If profile is not user's and user is logged in, include follow
else if(SessionAuth::isValid()) {
    if(FriendQuery::create()->filterByUsername($_SESSION['username'])->filterByFriendUsername($username)->exists()) {
        $btnClass = 'btn-danger';
        $btnText = "Unfollow";
    }
    else {
        $btnClass = 'btn-success';
        $btnText = "Follow";
    }

    $followButton = <<<EOT
            <div class='follow-btn-div'>
                <button action='button' class='btn btn-lg %s follow-btn' id='followBtn'>%s</button>
            </div>
            <script>
            var u = '%s';
            
            $('.btn-danger.follow-btn').click(function(event) {
                let btn = $(this);
                $.post({
                    url:'follow.php',
                    data:{func:'unfollow', u:u},
                    success:function(data) {
                        console.log(data);
                        if(data === 'Success') {
                            $(btn).html('Follow').removeClass('btn-danger').addClass('btn-success');
                        }
                    }
                });
            });
            $('.btn-success.follow-btn').click(function(event) {
                let btn = $(this);
                $.post({
                    url:'follow.php',
                    data:{func:'follow', u:u},
                    success:function(data) {
                        if(data === 'Success') {
                            $(btn).html('Unfollow').removeClass('btn-success').addClass('btn-danger');
                        }
                    }
                });
            });
            </script>
EOT;
    $followButton = sprintf($followButton, $btnClass, $btnText, $username);
}

//html
$content = <<<EOT
	<div class='row profile-body'>
		<div class='col-md-2 profile-info'>
			<div class='profile-img'>
				<img id="profileImg" src="%s" height="150" width="150" />
			</div>
			%s
            %s
		</div>
		<div class='col-md-8 profile-post'>
			%s
		</div>
		<div class='col-md-2 friend-info'>
            <h5>Friends List</h5>
                <ul class='list-group list-group-flush'>
			      %s
                </ul>
		</div>
	</div>
EOT;

$content = sprintf($content, $image, $imageUpload, $followButton, $posts, $friends);

include "template.php";
?>