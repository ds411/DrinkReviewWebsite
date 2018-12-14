<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If valid user and post is not empty, create post
if(isset($_POST['body']) && SessionAuth::isValid()) {

    //Post username
    $username = $_SESSION['username'];

    //Post body
    $body = htmlspecialchars($_POST['body']);

    //Replace mentions in post body with links
    $mentions = array();
    preg_match('/@[\w-]+/', $body, $mentions);
    foreach($mentions as $mention) {
        $u = substr($mention, 1);
        if(UserQuery::create()->findOneByUsername($u) !== null)
            $body = str_replace($mention, "<a href='profile.php?u=$u'>$mention</a>", $body);
    }

    //Post time
    $now = new DateTime();

    //New post
    $post = new Post();
    $post
        ->setCreationtime($now)
        ->setUsername($username)
        ->setBody($body)
        ->save();

    //Timestamp for html post
    $timestamp = $now->format('Y-m-d H:i:s');

    //User's name
    $name = $post->getUser()->getRealName();

    //html
    echo "<div class='feed-post'><p><a href='profile/?u=$username'>$name ($username)</a></p><p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
}
else {
    echo "ERROR: You cannot create a post.";
}



?>