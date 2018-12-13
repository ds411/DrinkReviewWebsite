<?php

session_start();

require "vendor/autoload.php";
require "database/generated-conf/config.php";
require "sessionAuth.php";

if(isset($_POST['page'])) {
    $friends = FriendQuery::create()
        ->select(array('Friend.Friend_Username'))
        ->findByUsername($_SESSION['username']);

    $offset = $_POST['page'] * 10 +  20;

    $feedPosts = PostQuery::create()
        ->where('Post.Username IN ?', $friends)
        ->_or()
        ->where('Post.Username = ?', $_SESSION['username'])
        ->orderByCreationtime('DESC')
        ->offset($offset)
        ->limit(10)
        ->find();

    if($feedPosts[0] === null) {
        echo "End.";
    }
    else {
        $feed = "";

        foreach($feedPosts as $post) {
            $username = $post->getUsername();
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
            $feed .=
                "<div class='feed-post'><p><a href='profile.php?u=$username'>$username</a>$drink</p>$rating<p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
        }

        echo $feed;
    }
}
else{
    echo "ERROR: No page value received.";
}
?>