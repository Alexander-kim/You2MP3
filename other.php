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

class other {

	/**
	 * @param string $folder_variable -> Folder name to be check if exist or not
	 */
	public function check_folder($folder_variable){
		if(!file_exists($folder_variable)){
			mkdir($folder_variable);
			file_put_contents($folder_variable."/.htaccess", "Options All -Indexes");
		}elseif(!file_exists($folder_variable."/.htaccess")){
			file_put_contents($folder_variable."/.htaccess", "Options All -Indexes");
		}
	}
	
	/**
	 * @param string file_variable -> Name of file that to be check if exist or not
	 */
	public function check_file($file_variable){
		if(!file_exists($file_variable)){
			file_put_contents($file_variable, "");
			file_put_contents(".htaccess", "<files ".$file_variable.">\r\nOrder allow,deny\r\nDeny from all\r\n</files>", FILE_APPEND);
		}
	}
	
	/**
	 * @param string $youtube_shortcut -> Youtube shortcut link to be convert to better link for conversion
	 * @return string -> Youtube better link
	 */
	public function original_link($youtube_shortcut){
		if(preg_match('/http:\/\/youtu.be\/.*/', $youtube_shortcut)){
			preg_match('/http:\/\/youtu.be\/(.*)/', $youtube_shortcut, $original_id);
			return "http://www.youtube.com/watch?v=".$original_id[1];
		}else{
			return $youtube_shortcut;
		}
	}
	
}


?>