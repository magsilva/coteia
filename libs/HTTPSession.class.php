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


class HTTPSession extends Session
{
	public function __construct()
	{
		if (session_name() == '') {
			@session_destroy();
			session_cache_expire($this->ttl);
			// none, nocache, public, private, private_no_expire
			session_cache_limiter('private');
		}
		session_start();
		session_regenerate_id();
	}
	
	public function __destruct()
	{
		session_write_close();
		@session_destroy();
	}

	public function beforeLogin()
	{	
		if ( $_SESSION == NULL || $_SESSION['vcard'] == NULL || ! isset($_SESSION['vcard']) ) {
			$url = "login_user.php";
			$url .= "?dest=" . $_SERVER["REQUEST_URI"];

			session_write_close();
			header( "Location: $url" );
			exit();
		}
	}
	
	public function afterLogin()
	{
		$this->vcard =& $_SESSION['vcard'];
	}	
}
?>