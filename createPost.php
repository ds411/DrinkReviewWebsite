<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(isset($_POST['body']) && SessionAuth::isValid()) {
    $username = $_SESSION['username'];
    $body = $_POST['body'];
    $now = new DateTime();

    $post = new Post();
    $post
        ->setCreationtime($now)
        ->setUsername($username)
        ->setBody($body)
        ->save();

    $timestamp = $now->format('Y-m-d H:i:s');
    echo "<div class='feed-post'><p><a href='profile/?u=$username'>$username</a></p><p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
}
else {
    echo "ERROR: You cannot create a post.";
}



?>