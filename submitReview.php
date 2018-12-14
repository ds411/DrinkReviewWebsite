<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//Valid login
$validSession = SessionAuth::isValid();

//Check if user already reviewed
$review = ReviewQuery::create()
    ->filterByDrinkId($id)
    ->usePostQuery()
    ->filterByUsername($_SESSION['username'])
    ->endUse()
    ->findOne();

//If valid login and user has not reviewed this drink, create review
if($validSession && $review === null) {
    $username = $_SESSION['username'];
    $body = $_POST['body'];
    $rating = number_format((float)$_POST['rating'], 2);
    $review = new Review();
    $post = new Post();
    $now = new DateTime();
    $post
        ->setUsername($username)
        ->setBody(htmlspecialchars($body))
        ->setCreationtime($now)
        ->save();
    $review
        ->setPost($post)
        ->setRating($rating)
        ->setDrinkId($_GET['d'])
        ->save();

    $timestamp = $now->format('Y-m-d H:i:s');

    //html
    echo "<div class='review-post'><p><a href='profile.php?u=$username' class='feed-user'>$username</a></p><p>Rating: $rating / 5</p><p class='feed-body'>$body</p><hr/><p class='feed-time'>Posted on $timestamp</p></div>";
}

?>