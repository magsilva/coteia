<?php
/**
* CoTeia's main page.
*
* Show the visible swikis and some usage statistics.
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

/* status das swikis:
0 = padrao (sem login e sem controle de concorrencia)
1 = login 

Parametro:
1 = adiciona swiki
2 = remove swiki
3 = atualiza swiki
*/

if ( ! isset( $_REQUEST[ "action" ] ) ) {
	$action = "view";
} else {
	$action = $_REQUEST[ "action" ];
}


$name = "";
$admin = "";
$admin_email = "";
$type = "";
$login = "";
$password = "";
$visible = "";
$semester = "";
$annotation = "";

if ( $action == "create" ) {
	$name = $_REQUEST[ 'name' ];
	$admin = $_REQUEST[ 'admin' ];
	$admin_email = $_REQUEST[ 'admin_email' ];
	$type = $_REQUEST[ 'type' ];
	$login = $_REQUEST[ 'login' ];
	$password = $_REQUEST[ 'password' ];
	$visible = $_REQUEST[ 'visible' ];
	$semester = $_REQUEST[ 'semester' ];
	$annotation = $_REQUEST[ 'annotation' ];

	$d = getdate();
	$date = $d["year"] . "-" . $d["mon"] . "-" . $d["mday"] . " " . $d["hours"] . ":" . $d["minutes"] . ":" . $d["seconds"];

	/**
	* Set up the chat session
	* You need to set the session name (any string) and the moderator's name (it can be an empty string: "")
	*/
	$dbh_chat = cs_db_pconnect();
	$cs_session_id = cs_session_create( $name, $login, $dbh_chat );

	/* Set up the annotation */
	// $annotation_folder_id = create_folder( $name, 0, 14, 9, 255 );
	$annotation_folder_id = 0;

	/* Setup iClass */
	$eclass = "";

	/**
	* Create the swiki.
	*/
	coteia_connect();
	$query = "insert into swiki (id,status,visivel,titulo,log_adm,admin,admin_mail,data,annotation_login,id_chat,id_ann,semestre,id_eclass) values (NULL,'$type','$visible','$name','$login','$admin','$admin_email','$date','$annotation','$cs_session_id','$annotation_folder_id','$semester','$eclass')";
	$result = mysql_query( $query );

	if ( $result == false ) {
		show_error( _( "It wasn't possible to create the swiki. If the error persist, contact the system administrator." ) );
	}

	$swiki_id = mysql_insert_id();
		
	$query = "update swiki set username='$login',password=md5('$password') where (id='$swiki_id')";
	$result = mysql_query( $query );
	if ( $result == false && mysql_affected_rows() != 1 ) {
		show_error( _( "It wasn't possible to create login and password for the swiki. If the error persist, contact the system administrator." ) );
	}

	/* Create upload directory. */
	if ( !is_dir( $UPLOADS_DIR . "/" . $swiki_id ) ) {
		$oldumask = umask( 0 );
		mkdir( $UPLOADS_DIR . "/" . $swiki_id, $DEFAULT_DIR_PERMISSION );
		umask( $oldumask );
	}
}


if ( $action == "delete" ) {
		$query_chat_ann = "select id_chat, id_ann from swiki where (id='$remove')";
		$sql_chat_ann = mysql_query("$query_chat_ann");
		$tupla_chat_ann = mysql_fetch_array($sql_chat_ann);
		$query = "delete from swiki where (id='$remove')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query($query,$dbh);
			
		
		// Pegar id_sw e achar id_pag para deletar paginas
		
		// Obtem sessao de chat - invoca a API do ChatServer 2.0
		// estabelece a conexao com o banco de dados do ChatServer 2.0
		$dbh_chat = cs_db_pconnect();
 		// chama a funcao de delecao de sessoes de chat
		// voce precisa informar a id da sessao a ser deletada - $session_id
		cs_session_delete($tupla_chat_ann["id_chat"], $dbh_chat);
		//-fim [sessao de chat]
		
		//-obtem id da pasta de anotacao
		$delete_id = delete_folder($tupla_chat_ann["id_ann"], 14, 9);
		//-fim [pasta de anotacao]
}

if ( $action == "update" ) {
	$query = "SELECT id,id_ann,titulo FROM swiki where id='$atualiza'";
	$sql = mysql_query( $query, $dbh );

	// Swiki not found
	if ( mysql_num_rows( $sql ) == 0 ) {
		$erro = 2;
		header( "Location:atualiza_swiki.php");
	}

	// Update swiki
	if ($passwd == "") {
		$query = "update swiki set titulo='$new_swiki',admin='$admin',admin_mail='$admail',username='$usuario',password=NULL,status='$status',visivel='$vis',semestre='$sem',id_eclass='$eclass',annotation_login='$ann_log' where (id='$atualiza')" or die ("Falha ao inserir no Banco de Dados");
	} else {
		$query = "update swiki set titulo='$new_swiki',admin='$admin',admin_mail='$admail',username='$usuario',password=md5('$passwd'),status='$status',visivel='$vis',semestre='$sem',id_eclass='$eclass',annotation_login='$ann_log' where (id='$atualiza')" or die ("Falha ao inserir no Banco de Dados");
	}

	$tuple = mysql_fetch_array( $sql );

	// Update annotation
 	$folder_name = set_folder_name( 14, 9, $tuple["id_ann"], $tuple["titulo"] );
	header("Location:setswiki.php");
}


echo get_header( _( "Create a New Swiki" ) );
?>
<script language="JavaScript">
function ValidaForm( form ) {
	if ( form.name.value == 0 ||
			form.admin.value == 0 ||
			form.admin_email.value == 0 ) {
		alert( 'Os campos de nome, administrador e mail são de preenchimento obrigatório.' );
		return false;
	}

	if ( form.type.value == 1 && ( form.login.value == "" || form.password.value == "" ) ) {
		alert( 'Os campos login e senha devem ser preenchidos' );
		return false;
	}

	return true;
}    
</script>

</head>

<body>

<html>



<h2>Criar nova swiki</h2>

<form method="post" action="swiki.php" onSubmit="return ValidaForm(this);">

<input type="hidden" name="action" value="create" />

<br /><?php echo _( "Swiki's name" ); ?>
<br /><input type="text" value="<?php echo $name;?>" name="name" size="50" />

<br /><?php echo _( "Type of swiki" ); ?>
<br /><select name="type">
	<option value="0" <?php if ( $tipo == 0 ) echo " selected";?>>Default</option>;
	<option value="1" <?php if ( $tipo == 1 ) echo " selected";?>>Restricted (requires login)</option>;
</select>

<br /><?php echo _( "Name of the person responsable for the swiki" ); ?>
<br /><input type="text" value="<?php echo $admin;?>" name="admin" size="25" />
	
<br /><?php echo _( "Email of the person responsable for the swiki" ); ?>
<br /><input type="text" value="<?php echo $admin_email;?>" name="admin_email" size="25" />

<br /><?php echo _( "Login to be used to login in the swiki (required if swiki's type is 'Restricted')" ); ?>
<br /><input type="text" value="<?php echo $login;?>" name="login" size="25" />

<br /><?php echo _( "Password to be used to login in the swiki (required if swiki's type is 'Restricted')" ); ?>
<br /><input type="password" value="<?php echo $password;?>" name="password" size="25" />

<br /><?php echo _( "Visibility" ); ?>
<br /><select name="visible">
	<option value="S" <?php if ( $visible == "S") echo " selected";?>><?php echo _( "Visible" ); ?></option>
	<option value="N" <?php if ( $visible == "N" ) echo " selected";?>><?php echo _( "Invisible" ); ?></option>
</select>

<br /><?php echo _( "Semester" ); ?>
<br /><select name="semester">
	<option value="T" <?php if ( $semester == "T" ) echo " selected"; ?>><?php echo _( "Every semester" ); ?></option>
<?php
	$today = getdate();
	for ( $j = -2; $j <= 2; $j++ ) {
		echo "a";
		$year = $today[ 'year' ] + $j;

		$value = "1_" . $year;
		echo "<option value=\"$value\"";
		if ( $value == $semester ) {
			echo " selected";
		}
		if ( $semester == "" ) {
			if ( $j == 0 && $today[ 'month' ] <= 6 ) {
				echo " selected";
			}
		}
		echo ">";
		echo "1&ordm; " . _( "semester" ) . " - $year</option>";

		$value = "2_" . $year;
		echo "<option value=\"$value\"";
		if ( $value == $semester ) {
			echo " selected";
		}
		if ( $semester == "" ) {
			if ( $j == 0 && $today[ 'month' ] > 6 ) {
				echo " selected";
			}
		}
		echo ">";
		echo "2&ordm; " . _( "semester" ) . " - $year</option>";
	}
?>
</select>

<br /><?php echo _( "Private annotations" ); ?>
<br /><select name="annotation">
	<option value="S" <?php if ( $annotation == "S" ) echo " selected";?>><?php echo _( "Yes" );?></option>";
	<option value="N" <?php if ( $annotation == "N" ) echo " selected";?>><?php echo _( "No" );?></option>";
</select>

<br />
<br /><input type="submit" value="<?php echo _( "Create swiki" );?>"/>

</form>

</body>

</html>
