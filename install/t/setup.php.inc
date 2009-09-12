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

Copyright (C) 2004 Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

include_once( dirname(__FILE__) . "/../config.php" );
include_once( dirname(__FILE__) . "/../fileutils.inc.php" );

/**
* Customize PHP's error output (a shorter message, without HTML tags).
*/
function console_error_handler( $errno, $errstr, $errfile, $errline ) {
	echo "\n[ERROR ($errno)]\n\tFile: $errfile:$errline:\n\tMessage: $errstr\n";
}


/**
* Replaces variables within text files (like a template engine).
*/
function replace_vars( $squema )
{
	extract($GLOBALS);

	$sql_squema = "\$translated_squema = <<<END\n";
	$sql_squema .= file_get_contents( $squema );
	$sql_squema .= "END;\n";
	eval( $sql_squema );

	$sql_squema_file = fopen( str_replace( ".raw", "" , $squema ), "w" );
	fwrite( $sql_squema_file, $translated_squema );
	fclose( $sql_squema_file );
}


/**
* Setup a dir, changing its permissions.
*/
function setup_dir( $dir ) {
	global $DEFAULT_FILE_PERMISSION, $DEFAULT_DIR_PERMISSION;

	if ( !file_exists( $dir ) ) {
		echo "\nCreating directory $dir...";
		$old_mask = umask( 0 );
		mkdirr( $dir, $DEFAULT_DIR_PERMISSION, true );
		umask( $old_mask );
		echo "Ok";
	} else {
		echo "\nDirectory $dir already exist";
	}

	echo "\nSetting permissions for $dir...";
	chmod( $dir, $DEFAULT_DIR_PERMISSION );
	recursive_chmod( $dir, $DEFAULT_DIR_PERMISSION, $DEFAULT_FILE_PERMISSION );
	echo "\nOk";
}


?>
