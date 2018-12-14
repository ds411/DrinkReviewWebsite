<?php

session_start();

require "vendor/autoload.php";
require "database/generated-conf/config.php";
require "sessionAuth.php";

//If valid request
if(isset($_POST['page'])) {

    //Page offset
    $offset = $_POST['page'] * 10 +  20;

    //Mention string
    $mention = '@' . $_SESSION['username'];

    //Find posts containing mention string
    $feedPosts = PostQuery::create()
        ->where("Post.Body LIKE '%$mention%'")
        ->orderByCreationtime('DESC')
        ->offset($offset)
        ->limit(10)
        ->find();

    //Tell client no more requests
    if($feedPosts[0] === null) {
        echo "End.";
    }
    //Generate html from posts
    else {
        $feed = "";

        foreach($feedPosts as $post) {
            $username = $post->getUsername();
            $name = $post->getUser()->getRealName();
            $timestamp = $post->getCreationtime()->format('Y-m-d H:i:s');
            $body = $post->getBody();
            $feed .=
                "<div class='feed-post'><p><a href='profile.php?u=$username' class='feed-user'>$name ($username)</a></p><p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
        }

        echo $feed;
    }
}
else{
    echo "ERROR: No page value received.";
}
?>