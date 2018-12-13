<?php
ini_set('display_errors', 'on');
if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$page = "feed";

if(SessionAuth::isValid()) {
	if(isset($_GET['s']) && $_GET['s'] === 'mentions') include "mentions.php";
	else include "feed.php";

}
else {
	include "loginForm.php";
}
include "template.php";

?>
