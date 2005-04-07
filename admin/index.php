<?php
/**
* CoTeia's main page.
*
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/


include_once( "header.php.inc" );

echo get_header( _( "Main menu" ) );
?>
</head>

<body>

<div align="center">

<table border="1" cellspacing="0" cellpadding="5" class="box-table">
<tr>
	<th valign="middle" class="table-header"><?php echo _( "Available actions" ); ?></th>
</tr>
<tr>
	<td valign="middle" nowrap="nowrap">
		<a href="updateswiki.php"><?php echo _( "Update swiki" ); ?></a><br />
		<a href="swiki.php"><?php echo _( "Add swiki" ); ?></a><br />
		<a href="delswiki.php"><?php echo _( "Remove swiki" ); ?></a><br />
		<a href="useradmin.php"><?php echo _( "Users" ); ?></a><br />
		<a href="wikipageadmin.php"><?php echo _( "Wikipages" ); ?></a><br />
		<a href="errors.php"><?php echo _( "Check errors" ); ?></a><br />
		<a href="logout.php"><?php echo _( "Logout" ); ?></a><br />
	</td>
</tr>
</table>

</div>

</body>

</html>
