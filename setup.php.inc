<?php
/**
* CoTeia's setup tool.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
* This code is licenced under the GNU General Public License (GPL).
*/


include_once( dirname(__FILE__) . "/config.php" );

/**
* Customize PHP's error output (a shorter message, without HTML tags).
*/
function console_error_handler( $errno, $errstr, $errfile, $errline ) {
	echo "\n[ERROR ($errno)]\n\tFile: $errfile:$errline:\n\tMessage: $errstr\n";
}

/**
* Recursively change the files and directories' permissions.
*/
function recursive_chmod( $path2dir, $mode_dir, $mode_file ) {
	$dir = dir( $path2dir );
	while( ( $filename = $dir->read() ) !== false ) {
		$full_filename = $dir->path . "/" . $filename;
		if ( is_file( $full_filename ) ) {
			echo "\n\t$full_filename";
			chmod( $full_filename, $mode_file );
		}
		if ( is_dir( $full_filename ) ) {
			if ( $filename != "." && $filename != ".." ) {
				echo "\n$full_filename";
				chmod( $full_filename, $mode_dir );
				recursive_chmod( $full_filename, $mode_dir, $mode_file );
			}
		} 
	}
	$dir->close();
}

/**
* Recursively change the files and directories' owner and group.
*/
function recursive_chown( $path2dir, $owner, $group )
{
	$dir = dir( $path2dir );
	while( ( $filename = $dir->read() ) !== false ) {
		$full_filename = $dir->path . "/" . $filename;
		if ( $filename != "." && $filename != ".." ) {
			echo "\n\t$full_filename";
			if ( $owner != -1 ) {
				chown( $full_filename, $owner );
			}
			if ( $group != -1 ) {
				chgrp( $full_filename, $group );
			}
			if ( is_dir( $full_filename ) ) {
				recursive_chown( $full_filename, $owner, $group );
			}
		} 
	}
	$dir->close();
}

/**
* Replaces variables within text files (like a template engine).
*/
function replace_vars( $squema ) {
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

/*
 * Create a directory structure recursively
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.0
 * @param       string   $pathname    The directory structure to create
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function mkdirr($pathname, $mode = null)
{
	// Check if directory already exists
	if ( is_dir( $pathname ) || empty( $pathname ) ) {
		return true;
	}

	// Ensure a file does not already exist with the same name
	if ( is_file( $pathname ) ) {
		trigger_error( 'mkdirr() File exists', E_USER_WARNING );
		return false;
	}

	// Crawl up the directory tree
	$next_pathname = substr( $pathname, 0, strrpos( $pathname, DIRECTORY_SEPARATOR ) );
	if ( mkdirr( $next_pathname, $mode ) ) {
		if ( !file_exists( $pathname ) ) {
			return mkdir( $pathname, $mode );
		}
	}
	return false;
}

?>
