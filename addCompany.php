<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(!SessionAuth::isValid()) header("Location: company.php");
else {
    $title = 'Add a Company';

    if (!isset($_POST['name'], $_POST['location'], $_POST['description'])) {
        $content = <<<EOT
		<div class='add-company'>
			<form method='POST' action='addCompany.php' enctype="multipart/form-data">
				<label>Image for Comapny:</label>
				<input type='file' name='image' id='image'/><br/>
				<label>Company Name:</label>
				<input type='text' name='name' id='name' required /><br/>
				<label>Location:</label>
				<input type='text' name='location' id='location' placeholder='Country or State or City???' required /><br/>
				<label>Description:</label>
				<textarea name='description' id='description' required></textarea><br/>
				<input type='submit' /></br>
			</form>
		</div>
        <script>
        $('form').submit(function(event){
            event.preventDefault();
            var formData = new FormData(this);
            console.log(formData);
            $.post({
                url:$(this).attr('action'),
                data:formData,
                processData:false,
                contentType:false,
                success:function(data) {
                    console.log(data);
                }
            });
        });
        </script>
EOT;

        include 'template.php';
    } else {
        $company = new Company();
        $company
            ->setName($_POST['name'])
            ->setLocation($_POST['location'])
            ->setDescription($_POST['description']);

        if (isset($_FILES['image']) && getimagesize($_FILES['image']['tmp_name']) !== false) {
            if (filesize($_FILES['image']) > 1024 * 1024) {
                die("ERROR: File exceeds 1 MB.");
            } else {
                $dir = "images/";
                $newFileName = uniqid("", true) . pathinfo($_FILES['image']['name'])['extension'];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newFileName)) {
                    $company->setPicture($newFileName);
                } else {
                    die("ERROR: File could not be saved.");
                }
            }
        }

        $company->save();
    }
}
?>