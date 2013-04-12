<?php

/**
	You2MP3
	Copyright (C) 2013  Mohd Shahril

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

ob_start();
@error_reporting(0);
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);

echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>";

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
echo "<center><br><br><h1>First Run!</h1><br>";
echo "<p>It's look like this is first time you are using this script.</p>";
echo "<p>You must set your admin username & password before continue.</p>";

echo "
<br><br>
<form method=POST>
<table>
<tr><td>New Username</td><td> : </td><td><input type=TEXT name=username_change ></td></tr>
<tr><td>New Password</td><td> : </td><td><input type=PASSWORD name=password_change ></td></tr>
<tr><td>Repeat Password</td><td> : </td><td><input type=PASSWORD name=repeat_password ></td></tr>
<tr><td colspan=3 align=center><input type=submit name=submit_change_login value=\"Save\"></tr></tr>
</form>
</table>
<br><br>";

if(isset($_POST['submit_change_login'])){

	if($_POST['password_change'] != $_POST['repeat_password']){
		die("Your password do not match. Please try again.");
	}
	if(empty($_POST['username_change']) || empty($_POST['password_change']) || empty($_POST['repeat_password'])){
		die("Please fill out all the field.");
	}
	$options_file = file_get_contents("options.php");
	unset($_POST['submit_change_login']);
	unset($_POST['repeat_password']);
	$_POST['password_change'] = md5(sha1($_POST['password_change']));
	$options_file = str_replace("\$admin_username = \"\"", "\$admin_username = \"".htmlentities($_POST['username_change'])."\"", $options_file);
	$options_file = str_replace("\$admin_password = \"\"", "\$admin_password = \"".htmlentities($_POST['password_change'])."\"", $options_file);
	if(file_put_contents("options.php", $options_file)){
		echo "<script> $('form').empty();$('p').empty(); </script>";
		echo "Done! <br>Press below button to continue and have fun ! :)<br><br>";
		echo "<button type=\"button\" onclick=\"window.location.href='index.php'\">Continue >></button>";
		unlink(__FILE__);
	}else{
		echo "Failed to save options!";
	}
}


?>