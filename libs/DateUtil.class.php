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

Copyright (C) 2006 Marco Aur�lio Graciotto Silva <magsilva@gmail.com>
*/

class DateUtil
{
	/**
	* Simple function for retrieving the current timestamp in microseconds:
	*/
	function getMicrotime()
	{
		list ($usec, $sec) = explode(" ", microtime());
		return ((float) $usec + (float) $sec);
	}
	
	
	public static function getCurrentYear()
	{
		$today = getdate(); 
		$year = $today['year'];
		
		return $year;
	}


	public static function getCurrentSemester()
	{
		$today = getdate();
		if ( $today['mon'] <= '6') {
			$semester = 1;
		} else {
			$semester = 2;
		}
		
		return $semester;
	}
}
?>