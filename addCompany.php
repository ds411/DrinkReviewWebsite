<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

//Redirect if user not logged in
if(!SessionAuth::isValid()) header("Location: company.php");
else {
    $title = 'Add a Company';

    //If not receiving form, display page
    if (!isset($_POST['name'], $_POST['location'], $_POST['description'])) {

        //Form html
        $content = <<<EOT
        <h4>Add A Company</h4>
		<div class='add-company form-group'>
			<form method='POST' action='addCompany.php' enctype="multipart/form-data">
				<label>Image for Comapny:</label>
				<input type='file' name='image' id='image' class='form-control-file'/><br/>
				<label>Company Name:</label>
				<input type='text' name='name' id='name' class='form-control' required /><br/>
				<label>Location:</label>
				<input type='text' name='location' id='location' placeholder='Country or State or City...' class='form-control' required /><br/>
				<label>Description:</label>
				<textarea name='description' id='description' class='form-control' required></textarea><br/>
				<button type='submit' class='btn btn-primary'>Add Company</br>
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
                    if(data === "Success.") $('form')[0].reset();
                }
            });
        });
        </script>
EOT;

        include 'template.php';
    }
    //If receiving form, process it
    else {

        //New company
        $company = new Company();
        $company
            ->setName(htmlspecialchars($_POST['name']))
            ->setLocation(htmlspecialchars($_POST['location']))
            ->setDescription(htmlspecialchars($_POST['description']));

        //Check for valid picture upload
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

        //Save company
        if($company->save()) echo "Success.";
    }
}
?>