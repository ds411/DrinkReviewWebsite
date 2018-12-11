<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(!SessionAuth::isValid()) header("Location: drink.php");
else {

    $title = "Add a Drink";

    if (!isset($_POST['name'], $_POST['company'], $_POST['style'], $_POST['description'])) {
        $content = <<<EOT
		<div class='add-drink'>
			<form method='POST' action='addDrink.php' enctype="multipart/form-data">
				<label>Image:</label>
				<input type='file' name='image' id='image'/><br/>
				<label>Drink Name:</label>
				<input type='text' name='name' id='name' required/><br/>
				<label>Company:</label>
				<select name='company' id='company' required>
				    %s
				</select><br/>
				<label>Style:</label>
				<select name='style' id='style' required>
				    %s
				</select><br/>
				<label>Description:</label>
				<textarea name='description' id='description' required></textarea><br/>
				<input type="submit" /></br>
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
                contentType:false,
                success:function(data) {
                    console.log(data);
                }
            });
        });
        </script>
EOT;

        $styles = StyleQuery::create()
            ->find();
        $styleOptions = "";
        foreach ($styles as $style) {
            $name = $style->getStyle();
            $styleOptions .= "<option value='$name'>$name</option>";
        }

        $companies = CompanyQuery::create()
            ->find();
        $companyOptions = "";
        foreach ($companies as $company) {
            $name = $company->getName();
            $companyOptions .= "<option value='$name'>$name</option>";
        }

        $content = sprintf($content, $companyOptions, $styleOptions);


        include 'template.php';
    } else {
        $drink = new Drink();
        $drink
            ->setName($_POST['name'])
            ->setCompany($_POST['company'])
            ->setStyle($_POST['style'])
            ->setDescription($_POST['description']);

        if (isset($_FILES['image'])) {
            if (getimagesize($_FILES['image']['tmp_name']) === false) {
                die("ERROR: File is not a valid image.");
            } else if (filesize($_FILES['image']) > 1024 * 1024) {
                die("ERROR: File exceeds 1 MB.");
            } else {
                $dir = "images/";
                $newFileName = uniqid("", true) . pathinfo($_FILES['image']['name'])['extension'];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newFileName)) {
                    $drink->setPicture($newFileName);
                } else {
                    die("ERROR: File could not be saved.");
                }
            }
        }

        $drink->save();
    }
}
?>