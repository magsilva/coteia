<?php
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copyright (C) 2004 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/


include_once('DownloadableResource.class.php');

/**
 * Download a file from the swiki's repository.
 * 
 * @param wikipage_id [string] Identifier of a swiki
 * @param filename [string] Name of the file to be download.
 */
class Download
{
	private $resource;
	
	private $httpHeaders;
	
	private $file;
	
	private $isPartialDownload;
	
	private $requestedRange;

	public function __construct($resource)
	{
		if (! $resource instanceof DownloadableResource) {
			trigger_error("Illegal argument");
		}
			
		$this->resource = $resource;
		
		$this->httpHeaders = $this->getHttpRequestHeaders();
		
		$this->file = fopen($resource->getFilename(), 'r');
	}

	private function getHttpRequestHeaders()
	{
		$headers = array();
		if (function_exists('getallheaders') && getallheaders() !== FALSE) {
			$tmp = getallheaders();
			foreach ($tmp as $key => $value) {
				$headers[strtolower($key)] = $value;
			} 
		} else {
			foreach($_SERVER as $name => $value) {
				if (substr($name, 0, 5) == 'HTTP_') {
					$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
				}
			}
		}
		return $headers;
	}
	
	private function getFilesize($filename)
	{
		$size = filesize($filename);
		
		if (! $size) {
			$command = 'ls -l "' . $filename . '" | cut -d " " -f 6';
			// $command = 'ls -l "' . $file . '" | awk "{print $5}"';

			$size = exec($command);
		}
		
		if (is_int($size)) {
			// PHP's integer type is signed and many platforms use 32bit integers, so filesize() may return
			// unexpected results for files which are larger than 2GB. For files between 2GB and 4GB in size
			// this can usually be overcome by using the sprintf.
			$size = sprintf("%u", filesize($filename));
		}
		
		return $size;
	}


	function get_http_mdate()
	{
    	return gmdate("D, d M Y H:i:s",filemtime($SCRIPT_FILENAME))." GMT";
	}


	private function getContentLengthandRange()
	{
		$length = $this->getFilesize($this->file);
		
		if (isset($this->headers['http_range'])) {
			list($a, $range) = explode("=",$this->headers['http_range']);
			str_replace($range, "-", $range);
			$requested_length = $length - $range;
			header('HTTP/1.1 206 Partial Content');
			header('Content-Length: ' . $requested_length);
			header('Content-Range: bytes ' . $range . $requested_length . '/' . $length);
		} else {
			header('Content-Length: ' . $length);
		}
	}
	
	private function sendData()
	{
		if ($this->isPartialDownload) {
			$chunksize = 1024 * 1024;
			fseek($this->file, $this->requestedRange);
			while (! feof($this->file)) {
				$buffer = fread($this->file, $chunksize);
				print($buffer);
				flush();
			}
		} else {
			readfile($checked_file);
		}
	}

	// Caching: http://ontosys.com/php/cache.html
	private function setCacheHeaders()
	{
		$if_modified_since = preg_replace('/;.*$/', '', $this->httpHeaders['If-Modified-Since']);
		$gmtime = $this->get_http_mdate();
		
	    if ($if_modified_since == $gmtime) {
    	    header("HTTP/1.1 304 Not Modified");
        	exit;
    	} else {
    		// header('Pragma: public');
			header('Pragma: hack');
			header('Cache-Control: public, must-revalidate');
			$offset = 60 * 60 * 24 * 1;
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT');
	    }
	}

	private function setContentHeaders()
	{
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="' . $resource->getPrettyFilename() . '"');
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: ' . date('r', filemtime($this->file)));
		header('Accept-Ranges: bytes');
	}
}
?>
