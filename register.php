<?php
require_once 'core/init.php';

// exists() has the default type of post 
// thats why you dont have to specify it when calling the fn
if(Input::exists()) {
	//echo 'submitted';
	//echo Input::get('username');
	$validate = new Validator();
	$validation = $validate->check($_POST, [
		'username' => [
			'required' => true,
			'min' => 2,
			'max' => 20,
			'unique' => 'users'   
		],
		'password' => [
			'required' => true,
			'min' => 6
		],
		'password-confirm' => [
			'required' => true,
			'matches' => 'password'
		]
	]);

	
	if($validation->passed()) {
		echo 'Passed';
	} else {
		//print_r($validation->errors()); 
		foreach($validation->errors() as $error) {
			echo $error, '<br>';
		}
	}
	
}

?>
<form action="" method="POST">
	<div class="field">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username" value="<?= escape(Input::get('username')) ?>" autocomplete="off">
	</div>
	<div class="field">
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="" autocomplete="off">
	</div>
	<div class="field">
		<label for="password-confirm">Confirm your password:</label>
		<input type="password" name="password-confirm" id="password-confirm" value="" autocomplete="off">
	</div>

	<input type="submit" name="submit" value="Register">
</form>