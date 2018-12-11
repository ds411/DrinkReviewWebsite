<?php

$title = "Add a Drink";

$content = <<<EOT
		<div class='add-drink'>
			<form method='POST' action='#' enctype="multipart/form-data">
				<label>Image for Drink:</label>
				<input type='file' name='drinkImg' id='drinkImg'/><br/>
				<label>Drink Name:</label>
				<input type='text' name='drinkName' id='drinkName'/><br/>
				<label>Company:</label>
				<input type='text' name='drinkComp' id='drinkComp'/><br/>
				<label>Style:</label>
				<input type='text' name='drinkStyle' id='drinkStyle'/><br/>
				<label>Description:</label>
				<input type='text' name='drinkDesc' id='drinkDesc'/><br/>
			</form>
		</div>
EOT;

include 'template.php';

?>