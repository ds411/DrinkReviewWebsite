<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Register or Login";

//html
$content = <<<EOF
	<div class='row login-page'>
		<div class='col register-col form-group'>
			<h5>New? Create an account:</h5>
			<form action='register.php' method='POST'>
			    <p class='form-errors'></p>
				<label>Name</label>
				<input type='text' name='name' class='form-control'/>
				<label>Username</label>
				<input type='text' name='username' class='form-control'/>
				<label>Password</label><br/>
				<input type='password' name='password' class='form-control'/>
				<label>Confirm Password</label>
				<input type='password' name='confirmPassword' class='form-control'/>
				<br/>
				<button type='submit' id='registerBtn' class='btn btn-primary'>Register</button>
			</form>
		</div>
		<div class='col login-col form-group'>
			<h5>Already have an account? Login:</h5>
			<form action='login.php' method='POST'>
			    <p class='form-errors'></p>
				<label>Username</label>
				<input type='text' name='username' class='form-control'/>
				<label>Password</label>
				<input type='password' name='password' class='form-control'/>
				<br/>
				<button type='submit' id='loginBtn' class='btn btn-primary'>Login</button>
			</form>
		</div>
		<script>
		$("form").submit(function(event) {
		    event.preventDefault();
		    let form = $(this);
		    $.post({
		        url:form.attr("action"),
		        data:form.serialize(),
		        success:function(data) {
		            if(data.indexOf('<') === -1) {
		                form.find('.form-errors').html(data);
		            }
		            else{
		                location.reload();
		            }
		        }
		    });
		});
        </script>
	</div>
EOF;
?>