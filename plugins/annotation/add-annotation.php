<html>

<head>
	<title>Formulário GroupNote</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include_once("annotation-api.inc");  

function msg($str){
	echo '<center><b>$str</b><br/><a href="javascript:history.back()">Voltar</a></center>';
	echo "</body>";
	echo "</html>";
	exit();
}

// No caso de anotacoes internas  
$array_temp = explode( ".", $swiki_id );
$sw_id = $array_temp[ 0 ];
$is_login = annotationLogin( $swiki_id );

if ( $add_an ) {
	if ( $is_login == TRUE ) {
		// verifica se o grupo existe
		if ( !group_exists( $form_grupo ) ) {
         msg( "Grupo não existe!" );
		}
		// autentica o usuario
		$ret = authenticate_user( $form_login, $form_pass );
		if ( $ret == -1 ) {
			msg("Login inválido!");
		} else if ( $ret == 0 ) {
			msg( "Senha inválida!" );
		} else {
			$id_usuario = get_user_id( "$form_login" );
			$id_grupo = get_group_id( "$form_grupo" );
			if ( !user_exists_in_group( $id_usuario, $id_grupo ) ) {
				msg( "Usuário não pertence ao grupo!" );
			}
		}
	}
	$kwd[ "kwd1" ] = $form_kwd1;
	$kwd[ "kwd2" ] = $form_kwd2;
	$kwd[ "kwd3" ] = $form_kwd3;
	$ret = create_annotation( $id_pasta, $id_father, $id_usuario, $id_grupo, "text/xhtml", "0", "", $form_titulo, $kwd, $annotates, "", $form_texto );

	if ( $ret > 0 ) {
		include_once( "anotation.php" );
	} else {
		msg( "Erro na criação da anotação!" );
	}
} else {
?>
<h2>Anotação</h2>

<form name="form_criaAnot" method="post" action="add-annotation.php">
	<b>Título:</b>
	<br /><input type="text" name="form_titulo" size=30 />
	<br /><b>Conteúdo</b>
	<br /><textarea name="form_texto" cols=65 rows=10 wrap=virtual style="width: 100%"></textarea>
	<br />
<?php
	if ( $is_login == TRUE ) {
		echo "<br /><b>User:</b> <input type='text' name='form_login' size=8 />";
		echo "<br /><b>Grupo:</b> <input type='text' name='form_grupo' size=15 />";
		echo "<br /><b>Senha:</b> <input type='password' name='form_pass' size=8 />";
	}
?>
	<br /><input name="add_an" type="submit" value="enviar" />
	<br /><input name="canc_an" type="button" value="voltar para a página anterior" onClick="javascript:history.back()" />
	<input name="close_an" type="button" value="fechar a página" onClick="javascript:window.close()" />

	<input name="id_pasta" type="hidden" value="<?php echo $id_pasta?>" />
	<input name="id_usuario" type="hidden" value="<?php echo $id_usuario?>" />
	<input name="id_grupo" type="hidden" value="<?php echo $id_grupo?>" />
	<input name="pasta" type="hidden" value="<?php echo $pasta?>" />
	<input name="annotates" type="hidden" value="<?php echo $annotates?>" />
	<input name="id_father" type="hidden" value="<?php echo $id_father?>" />
	<input name="swiki_id" type="hidden" value="<?php echo $swiki_id?>" />
</form>

</body>

</html>
<?
}
?>
