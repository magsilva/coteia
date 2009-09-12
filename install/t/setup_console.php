#!/usr/bin/php

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

/**
* CoTeia's setup tool.
*/


include_once( dirname(__FILE__) . "/setup.php.inc" );


set_error_handler( "console_error_handler" );

echo "\nSettings directories permissions...";
printf( "\nThe current umask is %o", umask() );
printf( "\nFile permissions: %o", $DEFAULT_FILE_PERMISSION );
printf( "\nDirectory permissions: %o", $DEFAULT_DIR_PERMISSION );
setup_dir( $PATH_COWEB );
setup_dir( $PATH_COWEB . "/" . $XML_DIR );
setup_dir( $PATH_COWEB . "/" . $UPLOADS_DIR );
setup_dir( $PATH_COWEB . "/" . $OUTPUT_DIR );
setup_dir( $PATH_COWEB . "/" . $IMAGES_DIR );
setup_dir( $PATH_COWEB . "/" . $XSL_DIR );
setup_dir( $PATH_COWEB . "/" . $CSS_DIR );
setup_dir( $PATH_COWEB . "/" . $LOG_DIR );
$restrict_dirs = array( "$PATH_COWEB/doc", "$PATH_COWEB/$XML_DIR", "$PATH_COWEB/$UPLOADS_DIR", "$PATH_COWEB/$OUTPUT_DIR", "$PATH_COWEB/$XSL_DIR", "$PATH_COWEB/$LOG_DIR");
foreach ( $restrict_dirs as $current ) {
	printf( "\n\tDenying access to %s", $current );
	$htaccess = fopen( $current . "/.htaccess", "w+" );
	fwrite( $htaccess, "\nDeny from all" );
	fclose( $htaccess );
}
echo "\nOk\n";


echo "\nSetting files permissions ...";
touch( $PATH_COWEB . "/log.txt" );
chmod( $PATH_COWEB . "/log.txt", $DEFAULT_FILE_PERMISSION );
chmod( $PATH_COWEB . "/config.php", $DEFAULT_FILE_PERMISSION & 0667 );
$htaccess = fopen( $PATH_COWEB . "/.htaccess", "w+" );
fwrite( $htaccess, "\n<Files \"config.php\">\n\tDeny from all\n</Files>\n" );
fclose( $htaccess );
echo "\nOk\n";


if ( $DEFAULT_USER !== "" ) {
	echo "\nChanging file's owner to $DEFAULT_USER...";
	chown( $PATH_COWEB, $DEFAULT_USER );
	recursive_chown( $PATH_COWEB, $DEFAULT_USER, -1 );
	echo "\nOk\n";
}

if ( $DEFAULT_GROUP !== "" ) {
	echo "\nChanging file's group to $DEFAULT_GROUP...";
  chgrp( $PATH_COWEB, $DEFAULT_GROUP );
	recursive_chown( $PATH_COWEB, -1, $DEFAULT_GROUP );
	echo "\nOk\n";
}

// Setup the plugins
echo "\n\n##################################################################";
echo "\nSetting up plugins...";
foreach (glob( $PLUGINS_DIR . "/*" ) as $plugin ) {
	if ( is_dir( $plugin ) ) {
		echo "\nLooking for configuration file for plugin " . basename($plugin) . "...";
		if ( is_file( $plugin . "/setup.php" ) ) {
			echo "Ok";
			include( $plugin . "/setup.php" );
		} else {
			echo "Not found";
		}
	}
}
echo "\nOk";

echo "\nCreating the database schemas...";
foreach (glob("doc/tables/*.raw") as $raw_squema) {
	replace_vars( $raw_squema );
}
echo "\nOk\n";

?>
