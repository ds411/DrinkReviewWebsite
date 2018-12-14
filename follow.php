<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If valid login
if(SessionAuth::isValid()) {

    //Unfollow or follow
    $function = $_POST['func'];

    //User username
    $username = $_SESSION['username'];

    //Friend username
    $friendusername = $_POST['u'];

    //Follow friend
    if($function === 'follow') {
        $friend = new Friend();
        $friend
            ->setUsername($username)
            ->setFriendUsername($friendusername)
            ->save();
        echo "Success";
    }

    //Unfollow friend
    else if($function === 'unfollow') {
        $friend = FriendQuery::create()
            ->filterByUsername($username)
            ->filterByFriendUsername($friendusername)
            ->delete();
        echo "Success";
    }
}


?>