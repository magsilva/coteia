#!/usr/bin/php

<?php


include_once( dirname(__FILE__) . "/config.php" );
include_once( dirname(__FILE__) . "/cvs-api.php.inc" );

function setup_cvs() {
	global $PATH_COWEB, $CVS_CHECKOUT_DIR, $CVS_PASSFILE, $CVS_USERNAME, $CVS_PASSWORD, $CVS_METHOD, $CVS_ROOT, $CVS_MODULE, $CVS_BIN;

	cvs_login();

	if ( $CVS_METHOD == "local" ) {
		if ( strpos( $CVS_ROOT, $PATH_COWEB ) !== false ) {
			setup_dir( $CVS_ROOT );
			if ( !is_dir( "$CVS_ROOT/CVS_ROOT" ) ) {
				$cmdline = "$CVS_BIN -d $CVS_ROOT init";
				exec( $cmdline );
			}
			$writers = fopen( "$CVS_ROOT/CVSROOT/writers", "a+" );
			// TODO: Criar writers.v via rcs
			fwrite( $writers, "$CVS_USERNAME:$CVS_PASSWORD" );
			fclose( $writers );
			if ( !is_dir( "$CVS_ROOT/$CVS_MODULE" ) ) {
				mkdir( "$CVS_ROOT/$CVS_MODULE" );
			}
		}
	}

	// Restrict access to the cvs's password and repository files.
	$htaccess = fopen( $PATH_COWEB . "/.htaccess", "w+" );
	fwrite( $htaccess, "\n<Files \"" . basename( $CVS_PASSFILE ) . "\">\n\tDeny from all\n</Files>\n" );
	fclose( $htaccess );

}

set_error_handler( "console_error_handler" );

echo "\nPreparing CVS...";
setup_dir( $CVS_CHECKOUT_DIR );
setup_cvs();
echo "\nOk\n";

?>
