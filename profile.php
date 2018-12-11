<?php 

$title = "Profile";

$content = <<<EOT
	<div class='row profile-body'>
		<div class='col-md-2 profile-info'>
			<div class='profile-img'>
				150px x 150px
			</div>
			<div class='imgUpload'>
				<label for="file-upload" class="custom-file-upload">
				    <i class="fas fa-upload"></i> Change Image
				</label>
				<input id="file-upload" type="file"/>
			</div>
		</div>
		<div class='col-md-8 profile-post'>
			Users Posts
		</div>
		<div class='col-md-2 friend-info'>
			Users Friends
		</div>
	</div>
EOT;

include "template.php";
?>