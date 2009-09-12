<?php
/*
Display usage instructior for CoTeia, along side some general information.

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

include_once(dirname(__FILE__) . '/data/templates/presentation.inc.php');

echo get_header( _( "Help" ) );
?>
</head>

<body>


<h1><?php echo _( "Help" ); ?></h1>

<h2><?php echo _( "Table of content" ); ?></h2>
<ul>
	<li><a href="#about"><?php echo _( "About CoTeia" ); ?></a></li>
	<li>
		<a href="#sintax"><?php echo _( "Syntax" ); ?></a>
		<ul>
			<li><a href="#basichtml"><?php echo _( "Basic HTML" ); ?></a></li>
			<li><a href="#links"><?php echo _( "Creating links" ); ?></a></li>
			<li><a href="#uploads"><?php echo _( "Uploading files" ); ?></a></li>
		</ul>
	</li>
	<li><a href="#lock"><?php echo _( "Locking" ); ?></a></li>
	<li><a href="#contact"><?php echo _( "Sugestions and bug reports" ); ?></a></li>
</ul>
	

<a name="about"></a><h2><?php echo _( "About CoTeia" ); ?></h2>

<p><?php echo _( 'CoTeia is a asynchronous collaborative Web pages editing tool. Based on <a href="http://coweb.cc.gatech.edu/csl/9/">CoWeb</a>, a complete re-implementation was done by Carlos de Arruda Júnior. The resulting software was then deployed at <a href="http:/www.icmc.usp.br/">Instituto de Ciências Matemáticas e de Computação</a>, where it\'s in production till the present days.</p>' ); ?></p>


<a name="sintax"></a><h2><?php echo _( "Syntax" ); ?></h2>

<a name="basichtml"></a><h3><?php echo _( "Basic HTML" ); ?></h3>
<dl>
	<dt>&lt;b&gt;<?php echo _( "text" ); ?>&lt;/b&gt;</dt>
	<dd><?php echo _( "Bold" ); ?></dd>

	<dt>&lt;i&gt;<?php echo _( "text" ); ?>&lt;/i&gt;</dt>
	<dd><?php echo _( "Italic" ); ?></dd>

	<dt>&lt;hr /&gt;</dt>
	<dd><?php echo _( "Horizontal ruler" ); ?></dd>

	<dt>&lt;center&gt;<?php echo _( "text" ); ?>&lt;/center&gt;</dt>
	<dd><?php echo _( "Center the text" ); ?></dd>

	<dt>&lt;h1&gt;<?php echo _( "text" ); ?>&lt;/h1&gt;</dt>
	<dd><?php echo _( "Header (first level)" ); ?></dd>

	<dt>&lt;h2&gt;<?php echo _( "text" ); ?>&lt;/h2&gt;</dt>
	<dd><?php echo _( "Header (second level" ); ?></dd>

	<dt>&lt;h3&gt;<?php echo _( "text" ); ?>&lt;/h3&gt;</dt>
	<dd><?php echo _( "Header (third level" ); ?></dd>

	<dt>&lt;pre&gt;<?php echo _( "text" ); ?>&lt;/pre&gt;</dt>
	<dd><?php echo _( "Pre-formatted (monospaced) text" ); ?></dd>

	<dt>&lt;ul&gt;&lt;li&gt;<?php echo _( "item's text" ); ?>&lt;/li&gt;&lt;/ul&gt;</dt>
	<dd><?php echo _( "Unordered list" ); ?></dd>

	<dt>&lt;ol&gt;&lt;li&gt;<?php echo _( "text" ); ?>&lt;/li&gt;&lt;/ol&gt;</dt>
	<dd><?php echo _( "Ordered list" ); ?></dd>

	<dt>&lt;img src="<?php echo _( "image's filename" ); ?>" align="<?php echo _( "position" ); ?>" alt="<?php echo _( "alternative text" ); ?>"/&gt;</dt>
	<dd><?php echo _( "Image" ); ?></dd>

	<dt>&lt;table border="<?php echo _( "border size" ); ?>" width="<?php echo _( "table's width" ); ?>"&lt;tr&gt;&lt;td&gt;<?php echo _( "cell's data" ); ?>&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;</dt>
	<dd><?php echo _( "Table. The 'tr' begins a new line and 'td' a cell within the line" ); ?></dd>

	<dt>&lt;a href="<?php echo _( "target's URL" ); ?><?php echo _( "text" ); ?>&lt;/a&gt;</dt>
	<dd><?php echo _( "External link" ); ?></dd>

	<dt>&lt;a href="mailto:<?php echo _( "email" ); ?>"&gt;<?php echo _( "text" ); ?>&lt;/a&gt;</dt>
	<dd><?php echo _( "Link to send an email" ); ?></dd>
</dl>


<a name="links"></a><h3><?php echo _( "Links" ); ?></h3>
<p><?php echo _( "To create a internal link (a link to another wikipage), use:" ); ?></p>
<pre>
	&lt;lnk&gt;<?php echo _( "text" ); ?>&lt;/lnk&gt;
</pre>


<a name="uploads"></a><h3><?php echo _( "Uploading files" ); ?></h3>
<p><?php echo _( 'You can upload files using the "Uploads" links in the toolbar. Then, you can create links to those files within the wikipages using the following syntax:' ); ?></p>

<pre>
	&lt;upl file="<?php echo _( "uploaded filename" ); ?>"&gt;<?php echo _( "text" ); ?>&lt;/upl&gt;
</pre>


<a name="lock"></a><h3><?php echo _( "Locking" ); ?></h3>
<p><?php echo _( "It's possible to block the writing access to a wikipage, requiring a password to do any modification into the page's content." ); ?></p>


<a name="contact"></a><h3><?php echo _( "Sugestions and bug reports" ); ?></h3>
<p><?php echo _( 'Any bug found or sugestions can be reported using the CoTeia\'s development page at <a href="http://incubadora.fapesp.br/projects/coteia">Incubadora Fapesp</a>.' ); ?></p>


</body>

</html>
