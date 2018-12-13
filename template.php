<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body background='bg.png'>
	<!-- Bootstrap --> 
	<nav class='navbar navbar-expand-lg navbar-light bg-light'>
	  <a class='navbar-brand' href='index.php'>Drinksite</a>
		<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarTogglerDemo02' aria-controls='navbarTogglerDemo02' aria-expanded='false' aria-label='Toggle navigation'>
		   <span class='navbar-toggler-icon'></span>
		</button>
		<div class='collapse navbar-collapse' id='navbarTogglerDemo02'>
			<ul class='navbar-nav mr-auto mt-2 mt-lg-0'>
				<li class='nav-item active'>
				   <a class='nav-link' href='index.php''>Home<span class='sr-only'>(current)</span></a>
				</li>
				<li class='nav-item'>
				  <a class='nav-link' href='drinkList.php''>Browse Drinks</a>
				</li>
				<li class='nav-item'>
				  <a class='nav-link' href='companyList.php'>Browse Companies</a>
				</li>
			</ul>
				<form class='form-inline my-2 my-lg-0'>
				  <input class='form-control mr-sm-2' type='search' placeholder='Search'>
				  <button class='btn btn-outline-success my-2 my-sm-0' type='submit'>Search</button>
				</form>
			<!-- Only show if logged in -->
			<ul class='navbar-nav mt-2 mt-lg-0' style='margin-left: 10px;'>
				<li class='nav-item dropdown'>
				  <a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#' role='button'>Profile</a>
				  <div class='dropdown-menu'>
				  	<a class='dropdown-item' href='profile.php'>Visit Profile</a>
				  	<div class="dropdown-divider"></div>
				  	<a class='dropdown-item' href='#'>Logout</a>
				  </div>
				</li>
			</ul>
			<!-- -->
		</div>
	</nav>

	<div class='container'>
		<?php echo $content ?>
	</div>
<?php
//print($token);
?>
</body>
</html>