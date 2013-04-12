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

require_once("options.php");

if($debug_mode != true){
	@error_reporting(0);
	@ini_set('error_log',NULL);
	@ini_set('log_errors',0);
}else{
	error_reporting(E_ALL);
	@ini_set('display_errors','On');
	@ini_set('error_log','my_file.log');
	@ini_set('error_log','');
	@ini_set('error_log','/dev/null'); #linux
}

@ini_set('max_execution_time',0);

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
echo "<center><br><br><h1>Admin Panel</h1><br>";

$cookie_value = md5($_SERVER['HTTP_HOST']."you2mp3".$admin_password);

if(!empty($_POST['admin_username']) && !empty($_POST['admin_password'])){
	if(md5(sha1($_POST['admin_password'])) != $admin_password || $_POST['admin_username'] != $admin_username){
		print_login();
		die("Incorrect Credential!");
	}else{
		setcookie("you2mp3", $cookie_value, time()+(60*(60*$admin_cookie)));
		echo "<script>window.location.href='".basename($_SERVER['PHP_SELF'])."?settings=option'</script>";
	}
}elseif(isset($_COOKIE['you2mp3'])){
	if($_COOKIE['you2mp3'] != $cookie_value){ 
		print_login();
		die("Incorrect Cookie!");
	}
}elseif(!isset($_COOKIE['you2mp3'])){
	print_login();
	die();
}else{
	print_login();
	die();
}

echo "
<form method=GET>
<input type=SUBMIT name=settings value=\"option\"/>
\t<input type=SUBMIT name=settings value=\"log\">
\t<input type=SUBMIT name=settings value=\"change login\">
</form>
";

if(isset($_GET['logout'])){
	if(isset($_COOKIE['you2mp3'])){
		setcookie("you2mp3", "", time()-3600);
		echo "<script>location.reload()</script>";
	}
}

echo "<button style=\"position:absolute;top:0;right:120;width:110px;\" onclick=\"window.location.href='index.php'\"><< Go Back to Main Menu</button>";
echo "<button style=\"position:absolute;top:0;right:0;width:110px;\" onclick=\"window.location.href='".basename($_SERVER['PHP_SELF'])."?logout'\">Logout</button>";

if(isset($_GET['settings'])){
	if($_GET['settings'] == "option"){
		if($debug_mode){
			$debug_select = "<option value=\"true\" selected=\"selected\">true</option>\r\n<option value=\"false\">false</option>";
			$debug_before = "true";
		}else{
			$debug_select = "<option value=\"true\">true</option>\r\n<option value=\"false\" selected=\"selected\">false</option>";
			$debug_before = "false";
		}
		if($logging){
			$select = "<option value=\"true\" selected=\"selected\">true</option>\r\n<option value=\"false\">false</option>";
			$logging_before = "true";
		}else{
			$select = "<option value=\"true\">true</option>\r\n<option value=\"false\" selected=\"selected\">false</option>";
			$logging_before = "false";
		}
		if(!empty($quality)){
			if($quality == "high"){
				$select_quality = "<option value=high selected=\"selected\">high</option>/r/n<option value=medium >medium</option>/r/n<option value=low>low</option>";
			}elseif($quality == "medium"){
				$select_quality = "<option value=high>high</option>/r/n<option value=medium selected=\"selected\">medium</option>/r/n<option value=low>low</option>";
			}elseif($quality == "low"){
				$select_quality = "<option value=high>high</option>/r/n<option value=medium >medium</option>/r/n<option value=low selected=\"selected\">low</option>";
			}
		}else{
			$select_quality = "<option value=high selected=\"selected\">high</option>/r/n<option value=medium >medium</option>/r/n<option value=low>low</option>";
		}
		if(!isset($_POST['settings_change'])){
			echo "
			<br><br>
			<form method=POST>
			<table>
			<tr><td>Video Quality</td><td> : </td><td>
			<select name=quality>
				".$select_quality."
			</select></td></tr>
			<tr><td>Temp Folder</td><td> : </td><td><input type=TEXT name=path value=\"".$path."\"></td></tr>
			<tr><td>Audio Folder</td><td> : </td><td><input type=TEXT name=mp3_path value=\"".$mp3_path."\"></td></tr>
			<tr><td>Download Threads</td><td> : </td><td><input type=range name=download_threads min=\"1\" max=\"100\" value=\"".$download_threads."\" onchange=\"rangethreadsvalue.value=value\"><output id=\"rangethreadsvalue\">".$download_threads."</output></td></tr>
			<tr><td>FFmpeg Path</td><td> : </td><td><input type=TEXT name=ffmpeg value=\"".$ffmpeg."\"></td></tr>
			<tr><td>Audio Quality</td><td> : </td><td><input type=range name=audio_bitrate min=\"1\" max=\"320\" value=\"".$audio_bitrate."\" onchange=\"rangevalue.value=value\"><output id=\"rangevalue\">".$audio_bitrate."</output></td></tr>
			<tr><td>Logging</td><td> : </td><td>
			<select name=\"logging\">
				".$select."
			</select></td></tr>
			<tr><td>Debug Mode</td><td> : </td><td>
			<select name=\"debug\">
				".$debug_select."
			</select></td></tr>
			<tr><td>Log File</td><td> : </td><td><input type=TEXT name=log_file value=\"".$log_file."\"></td></tr>
			<tr><td align=center colspan=3><input type=submit value=save></td></tr>
			<input type=hidden name=qualitybefore value=\"".$quality."\">
			<input type=hidden name=pathbefore value=\"".$path."\">
			<input type=hidden name=mp3_pathbefore value=\"".$mp3_path."\">
			<input type=hidden name=download_threadsbefore value=\"".$download_threads."\">
			<input type=hidden name=ffmpegbefore value=\"".$ffmpeg."\">
			<input type=hidden name=audio_bitratebefore value=\"".$audio_bitrate."\">
			<input type=hidden name=loggingbefore value=\"".$logging_before.":::log\">
			<input type=hidden name=debugbefore value=\"".$debug_before.":::debug\">
			<input type=hidden name=log_filebefore value=\"".$log_file."\">
			<input type=hidden name=settings_change value=true>
			</form>
			";
		}
	}elseif($_GET['settings'] == "log"){
		if($logging == true){
			if(!empty($log_file)){
				echo "<br><br>";
				$data = file_get_contents($log_file);
				$data = array_filter(explode("\r\n\r\n", $data));
				if(isset($_POST['delete_log_value'])){
					$get_options_file = file_get_contents($log_file);
					$get_options_file = str_replace($data[$_POST['delete_log_value']]."\r\n\r\n", "", $get_options_file);
					$get_options_file = str_replace($data[$_POST['delete_log_value']], "", $get_options_file);
					file_put_contents($log_file, $get_options_file);
					if(isset($_POST['delete_audio'])){
						preg_match('/audio\/(.*?)\//', $data[$_POST['delete_log_value']], $return);
						if(file_exists($mp3_path.$return[1])){
							rrmdir($mp3_path.$return[1]);
						}
					}
					echo "<script>location.reload()</script>";
				}
				if(count($data) < 1){
					die("Log file is empty!");
				}
				$count_log = 0;
				foreach($data as $pecah){
					echo "<table>";
					$pecah = explode("\r\n", $pecah);
					foreach($pecah as $pecah_lagi){
						$pecah_lagi = explode(" : ", $pecah_lagi);
						echo "<tr><td>".$pecah_lagi[0]."</td><td>".$pecah_lagi[1]."</td></tr>";
					}
					echo "<form method=POST><tr>
							  <input type=hidden name=delete_log_value value=".$count_log.">
							  <td></td>
							  <td>
							  <input type=submit name=delete_log value=\"Delete Log Only\">
							  <input type=submit name=delete_audio value=\"Delete Log with Audio\">
							  </td>
							  <td></td>
							  </tr></form>";
					echo "</table><br>";
					$count_log++;
				}
			}else{
				echo "<br><h3>Make sure log file variable didn't empty</h3>";
				die();
			}
		}else{
			echo "<br><h3>It's seem like you didn't enable log file</h3>";
			die();
		}
	}elseif($_GET['settings'] == "change login"){
		
		echo "
		<br><br>
		<form method=POST>
		<table>
		<tr><td>New Username</td><td> : </td><td><input type=TEXT name=username_change ></td></tr>
		<tr><td>New Password</td><td> : </td><td><input type=PASSWORD name=password_change ></td></tr>
		<tr><td>Repeat Password</td><td> : </td><td><input type=PASSWORD name=repeat_password ></td></tr>
		<tr><td colspan=3 align=center><input type=submit name=submit_change_login value=\"Change!\"></tr></tr>
		<input type=hidden name=username_before value=".$admin_username.">
		<input type=hidden name=password_before value=".$admin_password.">
		</form>
		</table>
		<br><br>
		";
		
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
			$_POST['password_change'] = md5($_POST['password_change']);
			foreach(array_combine(array_slice($_POST, 0, 2), array_slice($_POST, 2)) as $now => $before){
				$options_file = str_replace('"'.htmlentities($before).'"', '"'.htmlentities($now).'"', $options_file);
			}
			if(file_put_contents("options.php", $options_file)){
				echo "Saved!";
			}else{
				echo "Failed to save options!";
			}
		}
	}
}else{
	echo "<script>window.location.href='".basename($_SERVER['PHP_SELF'])."?settings=option'</script>";
}

if(isset($_POST['settings_change'])){
	$options_file = file_get_contents("options.php");
	unset($_POST['settings_change']);

	$first_array = array_slice($_POST, 0, 9);
	$second_array = array_slice($_POST, 9);
	foreach(array_combine($second_array, $first_array) as $before => $now){
		if($before != $now){
			if(strpos($before, ":::log")){
				$value = explode(":::", $before);
				$options_file = str_replace("\$logging = ".htmlentities($value[0]), "\$logging = ".htmlentities($now), $options_file);
			}elseif(strpos($before, ":::debug")){
				$value = explode(":::", $before);
				$options_file = str_replace("\$debug_mode = ".htmlentities($value[0]), "\$debug_mode = ".htmlentities($now), $options_file);
			}
		}
		$options_file = str_replace('"'.htmlentities($before).'"', '"'.htmlentities($now).'"', $options_file);
	}
	if(file_put_contents("options.php", $options_file)){
		echo "<script>location.reload()</script>";
	}else{
		echo "Failed to save options!";
	}
}

/**
 * This function is to delete folder and their subfolder recursively
 * @param string $dir -> Folder name or location of directory you want to delete
 */
function rrmdir($dir){
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
  }
}

/**
 * This function is to print out login form on screen
 */
function print_login(){
	echo "
	<button style=\"position:absolute;top:0;right:0;width:110px;\" onclick=\"window.location.href='index.php'\"><< Go Back to Main Menu</button>
	<form method=POST>
	<table>
	<tr><td>Username : </td><td><input type=TEXT name=admin_username size=20></td></tr>
	<tr><td>Password : </td><td><input type=password name=admin_password autocomplete=off size=20></td></tr>
	<tr><td align=center colspan=6><input type=SUBMIT value=\"Login\"></td></tr>
	</table>
	</form>
	<br><br>
	";
}


?>