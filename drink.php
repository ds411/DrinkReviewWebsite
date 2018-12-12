<?php 

$title = "DRINK NAME";
	
$content = <<<EOT
		<div class='jumbotron dc-container'>
			<div class='row dc-info'>
				<div class='col-md-2' style='background-color:red;'>
				drink img
				</div>
				<div class='col-md-6' style='background-color:blue;'>
				drink name, company, review average
				</div>
				<div class='col-md-4' style='background-color:red;'>
				other info about drink
				</div>
			</div>
		</div>
		<div class='drink-reviews'>
		user reviews
		</div>
EOT;

include 'template.php';

?>