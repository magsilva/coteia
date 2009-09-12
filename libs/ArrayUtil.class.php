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
 
Copyright (C) 2007 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

class ArrayUtil {

   /**
    * Deep array copy.
    * 
    * Make a complete deep copy of an array replacing references with deep
    * copies.
    * 
    * This has been writen by elkabong@samsalisbury.co.uk
    * (http://us3.php.net/manual/en/ref.array.php#71119).
    */
	public static function deepCopy($array, $copy = null)
	{
		if (! is_array($array)) {
			return;
		}
		
		if ($copy == null) {
			$copy = array();
		}
	
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				array_deep_copy($v, $copy[$k]);
			} else {
				$copy[$k] = $v;
			}
		}
		return $copy;
	}
	
	/**
    * Shallow array copy.
    * 
    * Make a complete shallow copy of an array.
    */
	public static function shallowCopy(&$array, $copy = null)
	{
		if (! is_array($array)) {
			return;
		}
		
		if ($copy == null) {
			$copy = array();
		}
	
		foreach ($array as $k => $v) {
			$copy[$k] = $v;
		}
		return $copy;
	}
	
	public static function diff($array1, $array2)
	{
		$array3 = array();
		foreach ($array1 as $key1 => $value1) {
			$found = FALSE;
			foreach ($array2 as $key2 => $value2) {
				if ($value1 == $value2) {
					$found = TRUE;
					break;
				}
			}
			if (! $found) {
				$array3[] = $value1;
			}
		}
		
		return $array3;
	}
}

?>