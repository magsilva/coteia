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

include_once(dirname(__FILE__) . '/data/templates/presentation.inc.php');

echo get_header(_('Credits'));
?>
</head>

<body>

<h1><?php echo $response['appName'] . ' v' . $response['appVersion'] . ' - Credits'; ?></h1>

<p>CoTeia has a long history. Its first version was released in 2001 and was entirely
developed by Carlos Roberto E. de Arruda Junior (aka Juninho). Several developers
from ICMC joined the effort shortly, contributing with new functionalities (annotation
service, frequency, version control):</p>

<ul>
	<li>Adriane Kaori Oshiro: Frequency plugin (2002).</li>
	<li>Carlos Roberto E. de Arruda Junior: Creator and main developer (2000 - 2002).</li>
	<li>Claudia Akemi Izeki: Frequency plugin (2002) and Annotation plugin (2002 - 2003).</li>
	<li>Daniel Carnio Junqueira: Helped Carlos with the CVS support (2002).</li>
	<li>Marco Aurélio Graciotto Silva: Main developer since 2003.</li>
</ul>

<p>Following this initial effort, on 2003 CoTeia's maintainership was transfered to
Marco Aurélio Graciotto Silva. It was released under an open source license (GPL) and
hosted at Incubadora FAPESP.</p>

<p>Although the heavy utilization and improvement along the years, CoTeia's code was
always 'messy' (as many other software's developed with PHP). Comparing the original
code (1.0) with the latest (1.5.2 or 2.0.0pre7) shows some evolution, but not what
we would expect from a mature software.</p>

<p>Since later 2006, a newer version has been under development. It's completely
object-oriented and throws away all the compability layer created along the years.
The database schema is the same, as it was sufficient for this new version's goals,
so older CoTeia's users can upgrade without fear.</p>


<h2>External libraries used by CoTeia</h2>

<p>CoTeia uses the FF-MVC FatFOX MVC, a MVC pattern implementation writen in PHP 5 
(http://www.phpclasses.org/browse/package/3715.html) developed by Marcio FatFOX
(marcio.gh@gmail.com). It has been heavily modified and, today, there isn't much
left from Marcio's code.</p>

</body>

</html>