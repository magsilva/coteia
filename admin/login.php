<?php
/**
* Authenticate an user as admin.
*/

include_once( dirname(__FILE__) . "/../function.php.inc" );

echo get_header( _( "Login" ) );
?>
</head>

<body>

<form name="login" method="post" action="index.php">
<br />
<div align="center">
	<table border="1" cellspacing="0" cellpadding="2" class="box-table">
	<tr>
		<th>Login:</th>
		<td><input type="text" size="15" name="username" /></td>
	</tr>
	<tr>
		<th>Password:</th>
		<td><input type="password" size="15" name="password" /></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="login" value="<?php echo _( "Login" ); ?>" /></td>
	</tr>
	</table>
</div>
</form>

</body>

</html>
