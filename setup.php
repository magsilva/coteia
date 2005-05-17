#!/usr/bin/php

<?php

/**
* CoTeia's setup tool.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
* This code is licenced under the GNU General Public License (GPL).
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
$restrict_dirs = array( "$PATH_COWEB/doc", "$PATH_COWEB/libs", "$PATH_COWEB/$XML_DIR", "$UPLOADS_DIR", "$OUTPUT_DIR", "$PATH_COWEB/$XSL_DIR" );
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

// Run the setup.php found at each plugin's directory.
foreach ( glob( $PLUGINS_DIR . "/*" ) as $plugin ) {
	if ( is_dir( $plugin ) ) {
		echo "\nSetting up " . $plugin . "/setup.php...";
		if ( is_file( $plugin . "/setup.php" ) ) {
			include( $plugin . "/setup.php" );
			echo "\nOk";
		} else {
			echo "No configuration needed";
		}
	}
}

// Load the plugin's configuration (may be needed to create the
// database schemas)
foreach (glob( $PLUGINS_DIR . "/*" ) as $plugin ) {
	if ( is_file( $plugin . "/config.php" ) ) {
		include( $plugin . "/config.php" );
	}
}

echo "\nCreating the database schemas...";
foreach (glob("*.raw") as $raw_squema) {
	replace_vars( $raw_squema );
}
echo "\nOk\n";



?>
