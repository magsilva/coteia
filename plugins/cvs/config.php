<?php
/**
* Configuration for the cvs plugin.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
* This code is licenced under the GNU General Public License (GPL).
*/


include_once( dirname(__FILE__) . "/../../config.php" );

/**
* The command line to be used to run cvs.
* Default value: "cvs"
*/
$CVS_BIN = "/usr/bin/cvs";

/**
* The method used to access the cvs repository.
* Possible values: "local", "ext", "pserver"
* Default value: "local"
*/
$CVS_METHOD = "local";

/**
* The server hostname or IP address. Leave this blank if the CVS_METHOD is "local".
* Default value: "".
*/
$CVS_SERVER = "";

/**
* The cvs's repository root to the used.
* Default value: $FILES_DIR . "cvs/repository"
*/
$CVS_ROOT = $PATH_COWEB . "/" . $FILES_DIR . "/cvs/repository";

/**
* The cvs's module to be used.
* Default value: "html"
*/
$CVS_WIKIPAGE_MODULE = "html";
$CVS_UPLOAD_MODULE = "uploads";

/**
* The username to be used to access the repository.
* Default value: ""
*/
$CVS_USERNAME = "";

/**
* The password to be used to access the repository.
* Default value: ""
*/

$CVS_PASSWORD = "";

/**
* The directory where a copy of the repository (checkout) will be kept.
* Default value: $FILES_DIR . "cvs/localcopy"
*/
$CVS_CHECKOUT_DIR = $PATH_COWEB . "/" . $FILES_DIR . "/cvs/localcopy";

/**
* The name of the file that will hold the password for cvs access (needed when
* accessing via "pserver" method).
* Default value: $FILES_DIR . "cvs/.cvspass"
*/
$CVS_PASSFILE = $PATH_COWEB . "/" . $FILES_DIR . "/cvs/.cvspass";
