<?php

require_once( dirname(__FILE__) . "/config.php" );

/*
* Code based upon jhbuild (Copyright (C) 2001-2003  James Henstridge)
*/
# table used to scramble passwords in ~/.cvspass files
$cvs_crypt_table = array( 
    0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14, 15,
   16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31,
  114,120, 53, 79, 96,109, 72,108, 70, 64, 76, 67,116, 74, 68, 87,
  111, 52, 75,119, 49, 34, 82, 81, 95, 65,112, 86,118,110,122,105,
   41, 57, 83, 43, 46,102, 40, 89, 38,103, 45, 50, 42,123, 91, 35,
  125, 55, 54, 66,124,126, 59, 47, 92, 71,115, 78, 88,107,106, 56,
   36,121,117,104,101,100, 69, 73, 99, 63, 94, 93, 39, 37, 61, 48,
   58,113, 32, 90, 44, 98, 60, 51, 33, 97, 62, 77, 84, 80, 85,223,
  225,216,187,166,229,189,222,188,141,249,148,200,184,136,248,190,
  199,170,181,204,138,232,218,183,255,234,220,247,213,203,226,193,
  174,172,228,252,217,201,131,230,197,211,145,238,161,179,160,212,
  207,221,254,173,202,146,224,151,140,196,205,130,135,133,143,246,
  192,159,244,239,185,168,215,144,139,165,180,157,147,186,214,176,
  227,231,219,169,175,156,206,198,129,164,150,210,154,177,134,127,
  182,128,158,208,162,132,167,209,149,241,153,251,237,236,171,195,
  243,233,253,240,194,250,191,155,142,137,245,235,163,242,178,152
);


/**
* Translate wikipage_id to a CVS filename.
*/
function wikipage2cvs( $wikipage_id )
{
	global $PATH_COWEB, $OUTPUT_DIR;
	$format = "html";

	$filename = $OUTPUT_DIR . "/" . $format . "/" . $wikipage_id . "." . $format;

	return $filename;	
}


/*
* Code based upon jhbuild (Copyright (C) 2001-2003  James Henstridge)
*/
function cvs_password_scramble( $password ) {
	global $cvs_crypt_table;

	$scrambled_password = 'A';
	for ( $i = 0; $i < strlen( $password ); $i++ ) {
    $scrambled_password .= chr( $cvs_crypt_table[ ord( $password{ $i } ) ] );
	}
	return $scrambled_password;
}


/*
* Code based upon jhbuild (Copyright (C) 2001-2003  James Henstridge)
*/
function cvs_password_descramble( $password ) {
	global $cvs_crypt_table;

	$descrambled_password = "";
	for ( $i = 1; $i < strlen( $password ); $i++ ) {
    $descrambled_password .=  chr( $cvs_crypt_table[ ord( $password{ $i } ) ] );
	}
	return $descrambled_password;
}


/**
* Log in the cvs's repository.
*
* The current implementation just supports the authentication needed for "local"
* and "pserver" cvs's access method. "Local" actually doesn't require any action
* and "pserver" is a hack, writing a password file with the necessary data for
* automatically authentication.
*
* @returns <code>true</code>
*/
function cvs_login()
{
	global $CVS_METHOD, $CVS_USERNAME, $CVS_PASSWORD, $CVS_PASSFILE;

	if ( $CVS_METHOD === "pserver" ) {
		$pass_file = fopen( $CVS_PASSFILE, "w" );
		$root = cvs_get_root();
		fwrite( $pass_file, $root );
		fwrite( $pass_file, " " );
		fwrite( $pass_file, cvs_password_scramble( $CVS_PASSWORD ) );
		fclose( $pass_file );
	}

	return true;
}


/**
* Build the CVS_ROOT's string required by CVS.
*
* @returns A string in the format <code>[ ":" method ":" ] [ username "@" ] [ host ":" ] cvsroot</code>
* @returns <code>false</string> if any parameter is missing (actually, just the <code>cvsroot</code>
*          is mandatory.
*/
function cvs_get_root()
{
	global $CVS_METHOD, $CVS_SERVER, $CVS_ROOT, $CVS_USERNAME;

	$result = "";
	if ( $CVS_METHOD != "" ) {
		$result .= ":" . $CVS_METHOD . ":";
	}
	if ( $CVS_USERNAME != "" && $CVS_METHOD !== "local" ) {
		$result .= $CVS_USERNAME . "@";
	}
	if ( $CVS_SERVER != "" && $CVS_METHOD !== "local" ) {
		$result .= $CVS_SERVER . ":";
	}
	if ( $CVS_ROOT == "" ) {
		return false;
	}
	$result  .= $CVS_ROOT;


	return $result;
}


/**
* Check if the repository has been checked out. If not, do it now. If it's
* checked out and <code>$update</code> is <code>true</code>, update the local
* repository (default is <code>$false</code>).
*
* @returns <code>true</code> if the module has been successfully checked out
*          or updated
* @returns <code>false</code> if the module couldn't be checked out or updated.
*/
function cvs_checkout( $module, $filename )
{
	global $CVS_CHECKOUT_DIR, $CVS_PASSFILE, $CVS_BIN;

	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );
	$cvs_root = cvs_get_root();

	if ( !is_file( $CVS_CHECKOUT_DIR . "/" . $module . "/" . $filename ) ) {
		// Checkout the module, if necessary
		$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' checkout '$module/$filename' 2>&1";
	} else {
		// Update the local repository.
		$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' update '$module/$filename' 2>&1";
	}
	exec( $cmdline, $output, $retval );

	if ( $retval != 0 ) {
		$result = false;
	} else {
		$result = true;
	}

	chdir( $old_dir );
	return $result;
}


/**
* Update a file.
*
* First it updates the wikipage in the local repository. If successful, it
* copies the newest HTML file to the local repository and then commits the
* change. 
*
* @returns <code>false</code> if it cannot update the file.
* @returns <code>true</code> if the update is sucessful.
*/
function cvs_update( $module, $srcFilename, $destFilename = " ", $comment = " " )
{
	global $CVS_CHECKOUT_DIR, $CVS_PASSFILE, $CVS_BIN;

	if ( $destFilename === " " ) {
		$destFilename = basename( $srcFilename );
	}

	if ( cvs_checkout( $module, $destFilename ) == false ) {
		return false;
	}

	// Add the file to the local repository.
	copy( $srcFilename, $CVS_CHECKOUT_DIR . "/" . $module . "/" . $destFilename );

	// Commit the changes made in $filename within the local repository.
	$cvs_root = cvs_get_root();
	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );
	$output = array();
	$retval = 0;
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' commit -m '$comment' '$module/$destFilename' 2>&1";
	exec( $cmdline, $output, $retval );
	if ( $retval != 0 ) {
		chdir( $old_dir );
		return false;
	}

	chdir( $old_dir );
	return true;
}


/**
* Add a new file to the CVS repository.
*
* @returns <code>true</code> if the file was successfully added.
* @returns <code>false</code> if the file wasn't successfully added. The root cause can be:
*          the file already exists in the repository; another error happened.
*/
function cvs_add( $module, $srcFilename, $destFilename = " ", $comment = " " )
{
	global $PATH_COWEB, $CVS_CHECKOUT_DIR, $CVS_PASSFILE, $CVS_BIN;

	$output = array();
	$retval = 0;
	$cvs_root = cvs_get_root();

	if ( $destFilename === " " ) {
		$destFilename = basename( $srcFilename );
	}

	if ( cvs_checkout( $module, $destFilename ) == false ) {
		return false;
	}

	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	// Copy the file to the local repository.
	copy( $srcFilename, $CVS_CHECKOUT_DIR . "/" . $module . "/" . $destFilename );

	// Mark the file as added.
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' add '$module/$destFilename' 2>&1";
	exec( $cmdline, $output, $retval );
	if ( $retval != 0 ) {
		chdir( $old_dir );
		return false;
	}

	// Commit the changes.
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' commit -m '$comment' '$module/$destFilename' 2>&1";
	unset( $output );
	exec( $cmdline, $output, $retval );
	if ( $retval != 0 ) {
		chdir( $old_dir );
		return false;
	}

	chdir( $old_dir );
	return true;
}


/**
* Retrieve the revisions for a file in the CVS repository.
*
* @param revision [string] CVS revision
* @return The revisions if the file exists, "false" otherwise.
*/
function cvs_get_revisions( $module, $filename )
{
	global $CVS_PASSFILE, $CVS_BIN;

	if ( cvs_checkout( $module, $filename ) === false ) {
		return false;
	}

	$cvs_root = cvs_get_root();
	$retval = 0;
	$revisions = array();
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' log -l '$module/$filename' | grep ^revision | cut -f 2 -d ' '";
	exec( $cmdline, $revisions, $retval );
	if ( $retval != 0 ) {
		return false;
	}

	return $revisions;
}


/**
* Retrieve the versions of the requested file.
*
* @returns An array with the files's versions, indexed by their dates.
* @returns <code>false</code> if the the cvs's log isn't available.
*/
function cvs_get_indexed_revisions( $module, $filename )
{
	global $CVS_PASSFILE, $CVS_BIN;


	$revisions = cvs_get_revisions( $module, $filename );
	if ( $revisions === false ) {
		return false;
	}

	$cvs_root = cvs_get_root();
	$retval = 0;
	$dates = array();
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' log -l '$module/$filename' | grep ^date | cut -f 2-3 -d ' '";
	exec( $cmdline, $dates, $retval );
	if ( $retval != 0 ) {
		return false;
	}

	$output = array();
	for ( $i = 0; $i < count( $revisions ); $i++ ) {
		$dates[ $i ] = str_replace( ";", "", $dates[ $i ] );
		$output[ $revisions[ $i ] ] = $dates[ $i ];
	}

	return $output;
}


/**
* Check if the given CVS revision is valid.
*
* @param revision [string] CVS revision
* @return The revision if it's valid, "false" otherwise.
*/
function cvs_has_revision( $module, $filename, $revision )
{
	$revisions = cvs_get_revisions( $module, $filename );
	if ( $revisions === false ) {
		return false;
	}

	foreach ( $revisions as $cur ) {
		if ( $revision == $cur ) {
			return $revision;
		}
	}

	return false;
}


/**
* Get the content of a given file's revision number.
*
* @returns <code>false</code> if the file couldn't be checked out.
* @returns the file's content if it could be sucessfully checked out.
*/
function cvs_checkout_revision( $module, $filename, $revision )
{
	global $CVS_PASSFILE, $CVS_BIN;

	if ( cvs_has_revision( $module, $filename, $revision ) ) {
		return false;
	}

	$cvs_root = cvs_get_root();
	$cmdline = "CVS_PASSFILE='$CVS_PASSFILE' $CVS_BIN -d '$cvs_root' checkout -p -l -r '$revision' '$module/$filename'";
	$pipe = popen( $cmdline, "r" );
	if ( $pipe === false ) {
		return false;
	}

	$content = "";
	while( $buffer = fgets( $pipe, 1024 ) ) {
		$content .= $buffer; 
	}
	pclose( $pipe );

	return $content;
}


?>
