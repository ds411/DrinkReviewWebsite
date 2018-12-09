<?php
include(merrimack/debug.php);
ini_set('display_errors', 'on');
session_start();

require "vendor/autoload.php";
require "database/generated-conf/config.php";

if(SessionAuth::isValid()) {
	include "feed.php";
}
else {
	include "loginPage.php";
}

?>
