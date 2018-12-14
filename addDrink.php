<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

if(!SessionAuth::isValid()) header("Location: drink.php");
else {

    $title = "Add a Drink";

    //If not receiving form, display page
    if (!isset($_POST['name'], $_POST['company'], $_POST['style'], $_POST['description'])) {

        //html
        $content = <<<EOT
        <h4>Add A Drink</h4>
		<div class='add-drink form-group'>
			<form method='POST' action='addDrink.php' enctype="multipart/form-data">
				<label>Image for Drink:</label><br/>
				<input type='file' name='image' id='image' class='form-control-file'/><br/>
				<label>Drink Name:</label>
				<input type='text' name='name' id='name' class='form-control' required/><br/>
				<label>Company:</label>
				<select name='company' id='company' class='form-control' required>
				    %s
				</select><br/>
				<label>Style:</label>
				<select name='style' id='style' class='form-control' required>
				    %s
				</select><br/>
				<label>Description:</label>
				<textarea name='description' id='description' class='form-control' required></textarea><br/>
				<button type="submit" class='btn btn-primary'>Add Drink</button></br>
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

        //Generate style options from DB
        $styles = StyleQuery::create()
            ->find();
        $styleOptions = "";
        foreach ($styles as $style) {
            $name = $style->getStyle();
            $styleOptions .= "<option value='$name'>$name</option>";
        }

        //Generate company options from DB
        $companies = CompanyQuery::create()
            ->find();
        $companyOptions = "";
        foreach ($companies as $company) {
            $id = $company->getId();
            $name = $company->getName();
            $companyOptions .= "<option value='$id'>$name</option>";
        }

        //Insert option lists to html
        $content = sprintf($content, $companyOptions, $styleOptions);


        include 'template.php';
    }
    //If receiving form, process it
    else {

        //New drink
        $drink = new Drink();
        //Drink style
        $style = StyleQuery::create()->findOneByStyle($_POST['style']);
        //Drink company
        $company = CompanyQuery::create()->findOneById($_POST['company']);

        $drink
            ->setName(htmlspecialchars($_POST['name']))
            ->setCompany($company)
            ->setStyle($style)
            ->setDescription(htmlspecialchars($_POST['description']));

        //Check for valid picture upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
            if (getimagesize($_FILES['image']['tmp_name']) === false) {
                die("ERROR: File is not a valid image.");
            } else if (filesize($_FILES['image']) > 1024 * 1024) {
                die("ERROR: File exceeds 1 MB.");
            } else {
                $dir = "images/";
                $newFileName = uniqid("", true) . pathinfo($_FILES['image']['name'])['extension'];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newFileName)) {
                    $drink
                        ->setPicture($newFileName);
                } else {
                    die("ERROR: File could not be saved.");
                }
            }
        }

        //Save drink
        if($drink->save()) echo "Success.";


    }
}
?>