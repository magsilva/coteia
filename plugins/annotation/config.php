<?php
/**
* GroupNode configuration file.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( dirname(__FILE__) . "/../../config.php" );

/**
* Database configuration for Groupnode.
*/
$groupnode_dbhost = $dbhost;
$groupnode_dbname = "groupnode";
$groupnode_dbuser = $dbuser;
$groupnode_dbpass = $dbpass;

/**
* Database configuration for user's data.
*/
$users_dbhost = $dbhost;
$users_dbname = "groupnode_users";
$users_dbuser = $dbuser;
$users_bpass = $dbpass;
?>
