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

require_once(dirname(__FILE__) . '/ModuleSetup.class.php');
require_once(dirname(__FILE__) . '/../libs/Util.class.php');

class CoTeiaSetup implements ModuleSetupJob
{
	public function getName()
	{
		return 'CoTeia setup';
	}
	
	public function getPriority()
	{
		return 0;
	}
	
	public function checkConfigFile()
	{
		return file_exists(dirname(__FILE__) . '/../config.php');
	}
	
	/**
	 * CoTeia requires the new object model for PHP 5 (pass by reference as
	 * default method).
	 */
	public function checkZend() 
	{
		return (! ini_get('zend.ze1_compatibility_mode'));
	}

	public function setup01SetupRootDirectory()
	{
		Util::setupDirectory($PATH_COWEB);
	}
	
	public function setup02SetupXMLDirectory()
	{
		Util::setupDirectory($XML_DIR);
	}

	public function setup03SetupUploadsDirectory()
	{
		Util::setupDirectory($UPLOADS_DIR);
	}

	public function setup04SetupOutputDirectory()
	{
		Util::setupDirectory($OUTPUT_DIR);
	}

	public function setup05SetupImagesDirectory()
	{
		Util::setupDirectory($IMAGES_DIR);
	}

	public function setup06SetupXSLDirectory()
	{
		Util::setupDirectory($XSL_DIR);
	}

	public function setup07SetupCSSDirectory()
	{
		Util::setupDirectory($CSS_DIR);
	}

	public function setup10DenyHTTPAccessForDataDirectory()
	{
		Util::htaccess_deny_dir($DATA_DIR);
	}

	public function setup10DenyHTTPAccessForLibsDirectory()
	{
		Util::htaccess_deny_dir($LIBS_DIR);
	}
	
	public function setup20SetupLogFilePermission()
	{
	
		touch( $PATH_COWEB . "/log.txt" );
		chmod( $PATH_COWEB . "/log.txt", $DEFAULT_FILE_PERMISSION );
	}
	
	public function setup21SetupConfigFilePermission()
	{
		chmod( $PATH_COWEB . "/config.php", $DEFAULT_FILE_PERMISSION & 0667 );
		Util::htaccess_deny_file($PATH_COWEB . "/config.php");
	}

	public function setup30SetOwnership()
	{
		if (getmyuid() != 0) {
			return true;
		}
		
		if ($DEFAULT_USER !== "") {
			chown( $PATH_COWEB, $DEFAULT_USER );
			recursive_chown( $PATH_COWEB, $DEFAULT_USER, -1 );
		}
		
		if ( $DEFAULT_GROUP !== "" ) {
			chgrp( $PATH_COWEB, $DEFAULT_GROUP );
			recursive_chown( $PATH_COWEB, -1, $DEFAULT_GROUP );
		}
	}

	public function setup40CreateDatabaseSchema()
	{
		foreach (glob("doc/tables/*.raw") as $raw_squema) {
			Util::replace_vars($raw_squema);
		}
	}	   
}
?> 