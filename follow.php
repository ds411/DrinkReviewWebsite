<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(SessionAuth::isValid()) {
    $function = $_POST['func'];
    $username = $_SESSION['username'];
    $friendusername = $_POST['u'];
    if($function === 'follow') {
        $friend = new Friend();
        $friend
            ->setUsername($username)
            ->setFriendUsername($friendusername)
            ->save();
        echo "Success";
    }
    else if($function === 'unfollow') {
        $friend = FriendQuery::create()
            ->filterByUsername($username)
            ->filterByFriendUsername($friendusername)
            ->delete();
        echo "Success";
    }
}


?>