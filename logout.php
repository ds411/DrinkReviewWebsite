<?php
session_start();

//end session
session_unset();

//Redirect to previous page or index otherwise
if(isset($_GET['t'])) header("Location: " . $_GET['t']);
else header('Location: index.php');