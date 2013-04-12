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

class you2mp3 extends http {

	/**
	 * @var array $forbidden -> List of forbidden word when renaming a file
	 */
	var $forbidden_name = array("\\", "/", ":", '?', '"', "<", ">", "|");

	/**
	 * @param string $video_link -> Youtube Link Video that need to get the video link
	 * @return string -> Return video link with extension.
	 */
	public function get_youtube_link($video_link){
		$source = $this->get_page($video_link);
		$data = $this->cutstr($source, 'url_encoded_fmt_stream_map', '</script>'); 
		$data = urldecode($data);
		$data = explode("http://", $data);
		$kumpul = array();
		foreach($data as $pecah){
			if(strpos($pecah, "youtube.com/") && strpos($pecah, "---") && preg_match('/s?ig=(.*?)(\,|\\\u)/', $pecah)){
				preg_match('/s?ig=(.*?)(\,|\\\u)/', $pecah, $out);
				$signature = "&signature=".$out[1];
				preg_match('/(.*?)(\,|\\\u)/', $pecah, $pecas);
				$pecas = $pecas[1];
				preg_match('/type=(.*?)(\;|\\\u)/', $pecah, $ext);
				$kumpul[] = "http://".$pecas.$signature."::::".$ext[1];
			}
		}
		return $kumpul;
	}
	
	/**
	 * @param array $array_video -> Get result from function get_youtube_link and process it.
	 * @param string $quality -> Set quality of video that need to return
	 * @return string -> Return Video URL with correct quality
	 */
	public function process_array_data($array_video, $quality = "medium"){
		$video_number_quality = $this->count_array_video($array_video, $quality);
		return $array_video[$video_number_quality];
	}
	
	/**
	 * @param string $path -> Get user output path to fix it
	 * @return string -> Return output path that have been fix
	 */
	public function fixpath($path){
		$first = substr($path, -1);
		if($first == "/"){
			return substr($path, 0, -1);
		}else{
			return $path;
		}
	}
	
	/**
	 * @param string $link -> Youtube link
	 * @param string $extension -> Extension of video file
	 * @return string -> Filename of output file
	 */
	public function get_video_file_name($link, $extension){
		global $forbidden_name;
		preg_match('/\<meta name=\".*\" content=\"(.*?)\"\>/', $this->get_page($link), $title);
		return str_replace($this->forbidden_name, "-", $title[1]).".".$this->extension($extension);
	}
	
	/**
	 * @param string $video_location -> Video input file
	 * @param string $output_location -> MP3 output location
	 * @param string $ffmpeg_location -> FFmpeg location
	 * @param string $bitrate -> Audio bitrate when converting video to mp3
	 * @return bool -> If audio conversion success, return true otherwise false
	 */
	public function video_to_mp3($video_location, $output_location, $ffmpeg_location, $bitrate = 320){ 

        if(system("\"".getcwd()."/".$ffmpeg_location."\" -i \"".getcwd()."/".$video_location."\" -vn -ac 2 -ar 44100 -ab ".$bitrate."k -f mp3 \"".getcwd()."/".$output_location."\"")){ 
            return true; 
        }else{ 
            return false; 
        } 
    } 
	
	/**
	 * @param string $audio_location -> Location where this script will put audio there
	 * @return string -> Location of audio location
	 */
	public function create_random($audio_location){
		$random = $this->fixpath($audio_location)."/".$this->random_string($length = 8);
		mkdir($random);
		$second = $random."/".$this->random_string($length = 8);
		mkdir($second);
		return $second;
	}
	
	/**
	 * @param string $length -> Length to create random string
	 * @return string -> Return random string
	 */
	private function random_string($length = 15){
		return substr(sha1(rand()), 0, $length);
	}
	
	/**
	 * @param string array $array_video -> Get result from function get_youtube_link and process it
	 * @param string $quality_option -> Decide which quality you want
	 * @return integer -> Return quality number (lower is better)
	 */
	private function count_array_video($array_video, $quality_option){
		if($quality_option == "high"){
			return 0;
		}elseif($quality_option == "medium"){
			return round(count($array_video)/2);
		}elseif($quality_option == "low"){
			return count($array_video) - 1;
		}
	}
	
	/**
	 * @param string $ext -> Get extension from youtube processing video function
	 * @return string -> Return file extension of that video
	 */
	private function extension($ext){
		switch($ext){
			case 'video/mp4' : return "mp4";
			case 'video/webm' : return "webm";
			case 'video/x-flv' : return "flv";
			case 'video/3gpp' : return "3gp";
			default : return "xxx";
		}
	}
	
	/**
	 * @param string $data -> Source string you want to cut.
	 * @param string $str1 -> Find string before text you want to cut.
	 * @param string $str2 -> Last string after text you want to cut.
	 * @return string -> Return string that have been cut.
	 */
	protected function cutstr($data, $depan, $belakang){ 
		$data = explode($depan, $data); 
		$data = explode($belakang, $data[1]); 
		return $data[0]; 
	}
	
}

?>