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

require_once("http.php");
require_once("functions.php");
require_once("options.php");
require_once("other.php");

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

if(empty($admin_username) && empty($admin_password)){
	echo "<script>window.location.href='first_run.php'</script>";
}

@ini_set('max_execution_time',0);
@ini_set('memory_limit', '-1');

date_default_timezone_set('Asia/Kuala_Lumpur'); //In new PHP, this is require

$other = new other;

$other->check_folder($path);
$other->check_folder($mp3_path);
if($logging){ $other->check_file($log_file); }

if(isset($_POST['youtube_link'])){ $link = htmlentities($_POST['youtube_link']); }else{ $link = ""; }

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\"><br>\r\n";
echo "<button style=\"position:absolute;top:0;right:0;width:110px;\" onclick=\"window.location.href='admin.php'\">Admin Panel >></button>";

?>

<center>
<br>
<h1>You2MP3 0.1.5 (beta)</h1>

<br><br>
<form method=post name=give_youtube action="#" onSubmit="return checklink()">
<input type=text name=youtube_link value='<?php echo $link; ?>' onchange="checklink()" size=100><br><br>
<input type=submit value="Get MP3 now !">
</form>

<br><br>
<p id="change"></p>

<script>

var checklink_regex = /http:\/\/(www.|)youtu(.be|be.com)\/.*/;

/**
 * Check youtube link in user input
 * @return bool -> If user input is valid youtube link, then return true, otherwise false
 */
function checklink(){
	var formyoutube_link = htmlentities(document.give_youtube.youtube_link.value);
	if(!checklink_regex.test(formyoutube_link)){
		document.getElementById('change').innerHTML="Link is invalid!<br>";
		return false;
	}else{
		return true;
	}
}

/**
 * htmlentities for javascript
 * @return string -> htmlentities output
 */
function htmlentities (string, quote_style, charset, double_encode) {
  var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
    symbol = '';
  string = string == null ? '' : string + '';

  if (!hash_map) {
    return false;
  }

  if (quote_style && quote_style === 'ENT_QUOTES') {
    hash_map["'"] = '&#039;';
  }

  if (!!double_encode || double_encode == null) {
    for (symbol in hash_map) {
      if (hash_map.hasOwnProperty(symbol)) {
        string = string.split(symbol).join(hash_map[symbol]);
      }
    }
  } else {
    string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function (ignore, text, entity) {
      for (symbol in hash_map) {
        if (hash_map.hasOwnProperty(symbol)) {
          text = text.split(symbol).join(hash_map[symbol]);
        }
      }

      return text + entity;
    });
  }

  return string;
}

</script>

<!-- Check if user browser disable javascript -->
<noscript>
Your browser seem didn't support javascript, some things may be unfunctional
</noscript>


<?php

$open = new you2mp3;

if(isset($_POST['youtube_link']) && $_POST['youtube_link'] != ""){
	//if($other->check_youtube_link($_POST['youtube_link']) != true){ die("<br><br>Link is invalid!<br>"); }
	$_POST['youtube_link'] = $other->original_link($_POST['youtube_link']);
	while(true){
		$array_link = $open->get_youtube_link($_POST['youtube_link']);
		$process_data = $open->process_array_data($array_link, $quality);
		$split_data = explode("::::", $process_data);
		if($open->check($split_data[0])){
			break;
		}
	}
	$link_download_video = $split_data[0];
	$link_download_extension = $split_data[1];
	$location_video = $open->fixpath($path)."/".$open->get_video_file_name($_POST['youtube_link'], $link_download_extension);
	if($open->download($link_download_video, $location_video, $download_threads)){
		$mp3_filename = substr($open->get_video_file_name($_POST['youtube_link'], $link_download_extension), 0, -4).".mp3";
		$set_output = $open->create_random($mp3_path)."/".$mp3_filename;
		$open->video_to_mp3($location_video, $set_output, $ffmpeg, $audio_bitrate);
		unlink($location_video);
		if($logging){	
			file_put_contents($log_file, "IP Address : ".$_SERVER['REMOTE_ADDR']."\r\n", FILE_APPEND);
			file_put_contents($log_file, "Date : ".date("j F Y")."\r\n", FILE_APPEND);
			file_put_contents($log_file, "Time : ".date("g:i a")."\r\n", FILE_APPEND);
			file_put_contents($log_file, "Name : ".substr($mp3_filename, 0, -4)."\r\n", FILE_APPEND); 
			file_put_contents($log_file, "Youtube Address : ".$_POST['youtube_link']."\r\n", FILE_APPEND);
			file_put_contents($log_file, "Audio Location : ".$set_output, FILE_APPEND); 
			file_put_contents($log_file, "\r\n\r\n", FILE_APPEND); 
		}
		echo "<br>Finish ! <br>";
		echo "<a href=\"".$set_output."\">".$mp3_filename."</a>";
	}else{
		echo "<br><br>Failed To Download That Video!<br>";
	}
}



?>
