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

/**
 * @var string $admin_username -> Set for admin username.
 */
$admin_username = "";

/**
 * @var string $admin_password -> Set for admin password.
 */
$admin_password = ""; // In md5(sha1(your password)) for better protection ;)

/**
 * @var string $admin_cookie -> Set for (hour) time your login cookie will be existence.
 */
$admin_cookie = "24*7";

/**
 * @var bool $debug_mode -> Set false if you don't want any error to be print out (otherwise true)
 */
$debug_mode = true;
 
/**
 * @var string $quality -> Set for youtube video download quality. (low, low, low)
 */
$quality = "medium";

/**
 * @var string $download_threads -> Set for curl threads while downloading video from youtube.
 */
$download_threads = "16";
 
/**
 * @var string $path -> Set for temp location for video that will be download from youtube.
 */
$path = "temp/";

/**
 * @var string $mp3_path -> Set for mp3 save location.
 */
$mp3_path = "audio/";

/**
 * @var string $ffmpeg -> Set for ffmpeg executable location.
 */
$ffmpeg = "tool/ffmpeg.exe"; //set it yourself

/**
 * @var string $audio_bitrate -> Set for audio bitrate when converting
 * Note : If you set lower, size of MP3 will decrease, maximum of bitrate is 320, lower for maximum quality
 */
$audio_bitrate = "320";

/**
 * @var bool $logging -> Set true if you want log otherwise true
 */
$logging = true;

/**
 * @var string $log_file -> Set log file location. (only if you set true in $logging).
 */
$log_file = "loguser_file.txt";


?>