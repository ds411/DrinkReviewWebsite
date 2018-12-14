<?php
session_start();

require "vendor/autoload.php";
require "database/generated-conf/config.php";
require "sessionAuth.php";

//Find user by username
$user = UserQuery::create()->findPk($_POST['username']);

//If form invalid
if(!isset($_POST['username'], $_POST['password'])) {
    echo "ERROR: Malformed form data.";
}
//If user not in DB
else if(empty($user)) {
    echo "ERROR: Username does not exist.";
}
//If password incorrect
else if(!password_verify($_POST['password'], $user->getPassword())) {
    echo "ERROR: Incorrect password.";
}
//If no problems, create session
else {
    SessionAuth::initSession($_POST['username'], $user->getPermissions());
    require "feed.php";
    echo $content;
}

?>