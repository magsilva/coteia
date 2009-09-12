<?
/**
* Change swiki's configuration.
*
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/


include_once( "header.php.inc" );


// Check parameters
if ( ! isset( $_REQUEST[ "swiki_id" ] ) ) {
  show_error( _( "Missing parameter: 'swiki_id'" ), 0 );
}
$swiki_id = $_REQUEST[ "swiki_id" ];
if ( swiki_check_id( $swiki_id ) === false ) {
  show_error( _( "The parameter 'swiki_id' is invalid." ) );
}


db_connect();
$query = "select * from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	header( "Location: setswiki.php" );
	exit();
}


$tuple = mysql_fetch_array( $result );
$status = $tuple[ "status" ];
$title = $tuple[ "titulo" ];
$username = $tuple[ "username" ];
$password = "";
$admin = $tuple[ "admin" ];
$admin_mail = $tuple[ "admin_mail" ];
$visible = $tuple[ "visivel" ];
$semester = $tuple[ "semestre" ];
$eclass_id = $tuple[ "id_eclass" ];
$annotation_id = $tuple[ "annotation_login" ];

mysql_free_result( $result );

echo get_header( _( "Set swiki configuration" ) );
?>
	<script language="JavaScript">
		function ValidateForm( form ) {
			if ( form.swiki_id.value == 0 || form.admin.value == 0 || form.admin_mail.value == 0 ) {
				alert( '<?php echo _( "The fields name, administrator's name and email are mandatory." ); ?>' );
				form.swiki_id.focus();
				return false;
			}

			if ( form.status.value == 1 && ( form.password.value == 0 || form.username.value == 0 ) ) {
				alert( '<?php echo _( "The fields 'login' and 'password' are mandatory for restricted swikis." ); ?>' );
				form.password.focus();
				return false;
			}
		
			return true;
		}
	</script>
</head>

<h1><?php echo _( "Set swiki configuration" ); ?></h1>

 
<form method="post" action="function.php" onSubmit="return ValidateForm(this);">


<br />
<?php echo _( "Name" ); ?>
<input type="text" name="title" size="25" maxlength="70" value="<?php echo $title; ?>" />


<br />
<?php echo _( "Administrator's name" ); ?>
<input type="text" name="admin" size="25" maxlength="40" value="<?php echo $admin; ?>" />


<br />
<?php echo _( "Administrator's email" ); ?>
<input type="text" name="admin_mail" size="25" maxlength="50" value="<?php echo $admin_mail; ?>" />


<br />
<?php echo _( "Login (optional)" ); ?>
<input type="text" name="username" size="25" maxlength="10" value="<?php echo $username; ?>" />


<br />
<?php echo _( "Password (optional)" ); ?>
<input type="password" name="password" size="25" maxlength="10" value="<?php echo $password; ?>" />


<br />
<?php echo _( "Type" ); ?>
<select name="status">
	<option value="0" <?php if ( $status == "0" ) echo " selected"; ?>><?php echo _( "Standard" ); ?></option>
	<option value="1" <?php if ( $status == "1" ) echo " selected"; ?>><?php echo _( "Restricted" ); ?></option>
</select>


<br />
<?php echo _( "Visible" ); ?>
<select name="visibility">
	<option value="S" <?php if ( $visible == "S" ) echo " selected"; ?>><?php echo _( "Yes" ); ?></option>
	<option value="N" <?php if ( $visible == "N" ) echo " selected"; ?>><?php echo _( "No" ); ?></option>
</select>


<br />
<?php echo _( "Semester" ); ?>
<select name="semester">
	<option value="T" <?php if ( $semester == "T" ) echo " selected"; ?>><?php echo _( "Any" ); ?></option>
	<?php
		$today = getdate();
		$year = $today[ 'year' ];
		for ( $i = $year - 2; $i <= $year + 2; $i++ ) {
			for ( $j = 1; $j <= 2; $j++ ) {
				$aux = $j . "_" . $i;
				echo "\n\t<option value=\"$aux\"";
				if ( $semester == $aux ) {
					echo " selected";
				}
				echo ">$j&ordm / $i</option>";
			}
		}
		echo "\n";
	?>
</select>

<?php
	if ( function_exists( "ce_db_pconnect" ) ) {
?>
<br />
<?php echo _( "eClass course" ); ?>
<select name="eclass_id">
<?php
	$dbh_ce = ce_db_pconnect();

	# seleciona base de dados
	mysql_select_db( $ce_dbname, $dbh_ce );

  $query = "SELECT curso_id,nome,semestre FROM curso ORDER BY nome";
  $sql = mysql_query( $query, $dbh_ce );

  echo "<option value=\"0\">Nenhum Curso</option>\n";

  while ( $tupla = mysql_fetch_array( $sql ) ) {
		$curso_id = $tupla[ "curso_id" ];
		$nome = $tupla[ "nome" ];
		$semestre = $tupla[ "semestre" ];
		if ( $id_eclass == $curso_id ) {
			$sel = " selected";
		} else {
			$sel = "";
		}
		echo "<option value=\"$curso_id\" $sel>$nome - $semestre</option>\n";
	}
	?>
</select>
<?php
}
?>

<br />
<?php echo _( "Private annotations" ); ?>
<select name="annotation_id">
	<option value="S" <?php if ( $annotation_id == "S" ) echo " selected" ?>><?php echo _( "Yes" ); ?></option>
	<option value="N" <?php if ( $annotation_id == "N" ) echo " selected" ?>><?php echo _( "No" ); ?></option>
</select>


<br />
<input type="reset" name="reset" value="<?php echo _( "Reset" ); ?>" />
<input type="submit" name="submit" value="<?php echo _( "Submit" ); ?>" />
	
<input type="hidden" name="parametro" value="3" /> 
<input type="hidden" name="swiki_id" value="<?php echo $swiki_id; ?>" /> 
</form>

</body>

</html>
