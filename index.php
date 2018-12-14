<?php
ini_set('display_errors', 'on');
if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$page = "feed";

//If valid login, display feed or mentions page
if(SessionAuth::isValid()) {
	if(isset($_GET['s']) && $_GET['s'] === 'mentions') include "mentions.php";
	else include "feed.php";

}
//If user not logged in, display login page
else {
	include "loginForm.php";
}
include "template.php";

?>
