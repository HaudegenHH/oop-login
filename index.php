<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'core/init.php';

// echo Config::get('mysql/host'); // should be '127.0.0.1'


//$users = DB::getInstance()->query("SELECT * FROM users");
/*
$users = DB::getInstance()->get('users', ['username','=','User1']);

if($users->count()) {
	foreach($users as $user) {
		echo $user->username;
	}
}
*/

//$user = DB::getInstance()->query("SELECT * FROM users WHERE username = ?", ['Knut']);

// get * from users where username == knut
//$user = DB::getInstance()->get('users', ['username','=','Knut']); 

//$user = DB::getInstance()->query("SELECT * FROM users");

//if(!$user->count()) {
//	echo 'No user';
//} else {	
	//echo 'OK!';
	/*
	foreach($user->results() as $user) {
		echo $user->username . '<br>';
	}
	*/
//	echo $user->first()->username;
//}

/* ------------- Insert ----------------- */
/*
$userInsert = DB::getInstance()->insert('users', [
	'username' => 'Billy',
	'password' => '321654',
	'salt' => 'salt'
]);

// cause insert() returns true or false we can check:
if($userInsert){
	// success
}

*/

/* ------------- Update ----------------- */
/* ..with additional parameter, the id    */

$userUpdate = DB::getInstance()->update('users', 3, [
	'password' => 'newPassword',
	'username' => 'Bob'
]);
