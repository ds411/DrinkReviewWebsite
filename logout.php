<?php
session_start();

session_unset();

if(isset($_GET['t'])) header("Location: " . $_GET['t']);
else header('Location: index.php');