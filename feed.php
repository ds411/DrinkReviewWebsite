<?php
$title = "Your Feed";
$page = "feed";

$content = <<<EOT
	<div class='row feed-info'>
		<div class='col-2 feed-types'>
			<ul class="list-group list-group-flush">
			  <li class="list-group-item">
			  	<a href='#'> Your Feed </a>
			  </li>
			  <li class="list-group-item">
			  	<a href='#'> Website Feed </a>
			  </li>
			</ul>
		</div>
		<div class='col-10'>
			POSTS
		</div>
	</div>
EOT;

include 'template.php';
?>