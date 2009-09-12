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
 
Copyright (C) 2008 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/libs/');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/resources/');

require_once('Config.class.php');

$config = Config::instance();
?>

<html>

<head>

</head>

<body>

The following directories should be created and writeable:

<ul>
	<li><? echo dirname(__FILE__) . '/aspects'; ?></li>
	<li><? echo dirname(__FILE__); ?></li>
	<li><? echo $config->smartyCompileDir; ?></li>
	<li><? echo $config->smartyCacheDir; ?></li>
</ul>

</body>

</html>