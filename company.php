<?php 

$title = "COMPANY NAME";

$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2' style='background-color:red;'>
				company img
				</div>
				<div class='col-md-6' style='background-color:blue;'>
				company name, loc
				</div>
				<div class='col-md-4' style='background-color:red;'>
				other info about company
				</div>
			</div>
		</div>
		<div class='company-other'>
		other by company or related
		</div>
EOT;

include 'template.php';

?>