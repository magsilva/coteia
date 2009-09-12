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

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/libs/');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/resources/');

require_once('Config.class.php');
require_once('ArrayUtil.class.php');
require_once('ErrorHandler.class.php');
require_once('aoPHP/RuntimeWeaver.class.php');
require_once('ff-mvc/AutoMapping.class.php');
require_once('ff-mvc/RequestProcessor.class.php');

$config = Config::instance();

$error_handler = new ErrorHandler();

// $this->log = &Logging::instance();

$weaver = new RuntimeWeaver(dirname(__FILE__) . '/aspects', dirname(__FILE__));
$weaver->run();

$mapper = new AutoMapping(dirname(__FILE__));
$mapping = $mapper->getMapping();
$defaultAction = 'Index';
$request = array();
$response = array();

if (isset($_REQUEST['do'])) {
	$action = $_REQUEST['do'];
	$request = ArrayUtil::shallowCopy($_REQUEST, $request);
} else {
	$action = $defaultAction;
}

$controller = new RequestProcessor();
$controller->processRequest($request, $response, $action, $mapping);

// TODO: Comment this out!
$weaver->clean();
?>