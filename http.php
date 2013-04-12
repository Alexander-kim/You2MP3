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

class http {

	/**
	 * @param string $url -> Check if url is online or valid
	 * @return bool -> Result of checking.
	 */
	public function check($url){
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url );
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_VERBOSE,false);
		curl_setopt($ch,CURLOPT_TIMEOUT, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch,CURLOPT_SSLVERSION,3);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
		$page=curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($httpcode>=200 && $httpcode<402) return true;
		else return false;
	}
	
	/**
	 * @param string $file_source -> File URL that need to be download
	 * @param string $file_target -> Target loction after download that file
	 * @result bool -> If download sucess, return true otherwise false
	 */
	/*public function download($file_source, $file_target) {
		$rh = fopen($file_source, 'rb');
		$wh = fopen($file_target, 'w+b');
		if (!$rh || !$wh){
			return false;
		}
		while (!feof($rh)){
			if (fwrite($wh, fread($rh, 4096*10)) === FALSE){
				return false;
			}
		}
		fclose($rh);
		fclose($wh);
		return true;
	}*/
	
	/**
	 * @param string $file_source -> File URL that need to be download
	 * @param string $file_target -> Target loction after download that file
	 * @param integer $file_target -> Set for curl threads
	 */
	public function download($file_source, $file_target, $threads) {
		$size = $this->urlsize($file_source);
		if(1024*1024 > $size) $threads = 3;
		$splits = range(0, $size, round($size/$threads));
		$megaconnect = curl_multi_init();
		for ($i = 0; $i < sizeof($splits)-1; $i++) {
			$ch[$i] = curl_init();
			curl_setopt($ch[$i], CURLOPT_URL, $file_source);
			curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch[$i], CURLOPT_VERBOSE, 0);
			curl_setopt($ch[$i], CURLOPT_BINARYTRANSFER, 1);
			curl_setopt($ch[$i], CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT, 10);
				$x = ($i == 0 ? 0 : $splits[$i]+1);
				$y = ($i == sizeof($splits)-1 ? $size : $splits[$i+1]);
				$range = $x.'-'.$y;
			curl_setopt($ch[$i], CURLOPT_RANGE, $range);
			curl_multi_add_handle($megaconnect, $ch[$i]);
		}
		do {
			curl_multi_exec($megaconnect,$running);
		} while($running > 0);
		$data = '';
		for($i = 0; $i < sizeof($splits)-1; $i++) {
			$results = curl_multi_getcontent($ch[$i]);
			$data .= $results;
		}
		$put = file_put_contents($file_target, $data);
		for($i = 0; $i < sizeof($splits)-1; $i++) {
			curl_multi_remove_handle($megaconnect, $ch[$i]);
		}
		curl_multi_close($megaconnect);
		if($put){ return true; }else{ return false; }
	}
	
	/**
	 * @param string $url -> Page URl to get HTML source
	 * @return string -> Return HTML source of target URl
	 */
	protected function get_page($url){ 
		$ch = @curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$page = curl_exec( $ch); 
		curl_close($ch);  
		return $page; 
	}
	
	/**
	 * @param string $url -> Get url and then get the size of that url file
	 * @return integer -> Return the byte size of url file
	 */
	protected function urlsize($url) { 
		if (substr($url,0,4)=='http') { 
			$x = array_change_key_case(get_headers($url, 1),CASE_LOWER); 
			if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; } 
			else { $x = $x['content-length']; } 
		} 
		else { $x = @filesize($url); } 
		return $x; 
	} 
}

?>