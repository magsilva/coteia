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
 
Copyright (C) 2006 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

require_once('AOPHP.php');
require_once('FileUtil.class.php');

class RuntimeWeaver
{
	private $aoPHPWeaver;
	
	private $aspectsDir;
	
	private $outputDir;
	
	public function __construct($aspectsDir, $outputDir)
	{
		if (! FileUtil::isDir($aspectsDir)) {
			throw Exception('Invalid aspects directory');
		}
		if (! FileUtil::isDir($outputDir)) {
			throw Exception('Invalid aspects directory');
		}
		
		$this->aspectsDir = realpath($aspectsDir);
		$this->outputDir = realpath($outputDir);
		$this->aoPHPWeaver = new AOPHPWeaver();
		
		if (! is_writable($this->outputDir)) {
			trigger_error("Cannot weave the aspects at '$this->outputDir'", E_USER_ERROR);
		}
		if (! is_writable($this->aspectsDir)) {
			trigger_error("Cannot weave the aspects at '$this->outputDir'", E_USER_ERROR);
		}
	}
	
	public function run()
	{
		$inputFiles = glob($this->aspectsDir . '/*.php');
		$adviceFiles = glob($this->aspectsDir . '/*.aophp');
		
		$forceWeaving = false;
		foreach ($adviceFiles as $file) {
			if (! file_exists($this->getAdviceCacheFilename($file))) {
				$forceWeaving = true;
				break;
			}
			$oldAdviceMtime = filemtime($this->getAdviceCacheFilename($file));
			$currentAdviceMtime = filemtime($file);
			if ($currentAdviceMtime > $oldAdviceMtime) {
				$forceWeaving = true;
				break;
			}
		}
		
		if ($forceWeaving) {
			$result = TRUE;
			foreach ($adviceFiles as $file) {
				$result &= touch($this->getAdviceCacheFilename($file));
			}
			if ($result === FALSE) {
				trigger_error('Cannot update the advices', E_USER_WARNING);
				$result = TRUE;
				foreach ($inputFiles as $file) {
					$result &= unlink($file);
				}
				if ($result === FALSE) {
					trigger_error('Cannot update the advices', E_USER_ERROR);
				}
			}
		}
			
		foreach ($inputFiles as $input) {
			$input = realpath($input);
			if ($input == realpath(__FILE__)) {
				continue;
			}

			$outputFile = $this->outputDir . '/' . basename($input);
			if (file_exists($outputFile)) {
				if (! $forceWeaving && filemtime($outputFile) > filemtime($input)) {
					continue;
				}
				unlink($outputFile);
			}
			$this->aoPHPWeaver->invoke($this->aspectsDir . '/', $input, $outputFile);
		}
	}
	
	public function clean()
	{
		$inputFiles = glob($this->aspectsDir . "/*.php");
		$adviceFiles = glob($this->aspectsDir . "/*.aophp");

		foreach ($inputFiles as $input) {
			if (realpath($input) == realpath(__FILE__)) {
				continue;
			}
			
			$outputFile = $this->outputDir . '/' . basename($input);
			if (file_exists($outputFile)) {
				unlink($outputFile);
			}
		}
		
		foreach ($adviceFiles as $file) {
			if (file_exists($this->getAdviceCacheFilename($file))) {
				unlink($this->getAdviceCacheFilename($file));
			}
		}
	}
	
	public function getAdviceCacheFilename($adviceFile)
	{
		$adviceFile = realpath($adviceFile);
		return dirname($adviceFile) . '/.' . basename($adviceFile);
	}
}

?>
