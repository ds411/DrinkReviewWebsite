<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//If valid login
if(SessionAuth::isValid()) {

    //Find user
    $user = UserQuery::create()
        ->findOneByUsername($_SESSION['username']);

    //Check if picture is valid, upload,  and save
    if (isset($_FILES['image']) && getimagesize($_FILES['image']['tmp_name']) !== false) {
        if (filesize($_FILES['image']) > 1024 * 1024) {
            die("ERROR: File exceeds 1 MB.");
        } else {
            $dir = "images/";
            $newFileName = $user->getPicture();
            if($newFileName === null) $newFileName = uniqid("", true) . pathinfo($_FILES['image']['name'])['extension'];
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newFileName)) {
                $user
                    ->setPicture($newFileName)
                    ->save();
                echo "<img id='profileImg' src='images/$newFileName' height='150' width='150' />";
            } else {
                die("ERROR: File could not be saved.");
            }
        }
    }
}

?>