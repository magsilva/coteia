<?php
/**
* Compatibility function wrappers.
*
* These are function's wrappers that make available functions found on newer
* PHP versions (mostly 4.3 ones).
*
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL)
*/


$version = explode( ".", phpversion() );
$major = $version[0];
$minor = $version[1];
$release = $version[2];

if ( $major < 4 || ( $major == 4 && $minor < 3 )  ) {

function glob( $pattern )
{
	$path_parts = pathinfo( $pattern );
	$pattern = '^' . str_replace( array ('*',  '?'), array( '(.+)', '(.)' ), $path_parts['basename'] . '$' );
	$dir = opendir( $path_parts['dirname'] );
	while ( $file = readdir( $dir ) ) {
		if ( ereg( $pattern, $file ) ) {
			$result[] = "{$path_parts['dirname']}/$file";
		}
	}
	closedir( $dir );
 
	if ( isset( $result ) ) {
		return $result;
	}
    
	return (array)null;
}

function file_get_contents( $filename, $use_include_path = 0)
{
	$content = file( $filename, $use_include_path );
	$content = implode( "", $content );
	return $content;
}


define( __FUNCTION__, "" );

} // END OF PHP 4.3

?>
