<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$validSession = SessionAuth::isValid();
$review = ReviewQuery::create()
    ->filterByDrinkId($id)
    ->usePostQuery()
    ->filterByUsername($_SESSION['username'])
    ->endUse()
    ->findOne();
if($validSession && $review === null) {
    $username = $_SESSION['username'];
    $body = $_POST['body'];
    $rating = number_format((float)$_POST['rating'], 2);
    $review = new Review();
    $post = new Post();
    $now = new DateTime();
    $post
        ->setUsername($username)
        ->setBody($body)
        ->setCreationtime($now)
        ->save();
    $review
        ->setPost($post)
        ->setRating($rating)
        ->setDrinkId($_GET['d'])
        ->save();

    $timestamp = $now->format('Y-m-d H:i:s');

    echo "<div class='review'><p><a href='profile.php?u=$username' class='feed-user'>$username</a></p>$rating<p class='review-time'>Posted on $timestamp</p><p class='review-body'>$body</p></div>";
}

?>