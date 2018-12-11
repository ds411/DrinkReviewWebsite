<?php

$title = 'Add a Company';

$content = <<<EOT
		<div class='add-company'>
			<form method='POST' action='#' enctype="multipart/form-data">
				<label>Image for Comapny:</label>
				<input type='file' name='companyImg' id='companyImg'/><br/>
				<label>Company Name:</label>
				<input type='text' name='companyName' id='companyName'/><br/>
				<label>Location:</label>
				<input type='text' name='companyLoc' id='companyLoc' placeholder='Country or State or City???'/><br/>
				<label>Description:</label>
				<input type='text' name='companyDesc' id='companyDesc'/><br/>
			</form>
		</div>
EOT;

include 'template.php';

?>