<?php
/*
 * NOME:                autentica
 * DESCRICAO:           Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:        $usuario - usuario & $passwd - senha 
 * PAR. SAIDA:          --
 * RETORNO:             TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:         --
 */       
 
include_once("function.inc");

$dbh = db_connect();
$retorno = login_swiki( $usuario, $passwd, $id, $dbh );


if ( $retorno ) {
	if ( $token == "1" ) {
		header( "Location:ok.php?id=$id" ); //Redireciona para a interface inicial
		exit;
	}

	if ( $token == "0" ) {
		header( "Location:login_create.php?id=$id&amp;index=$index" );
		exit;
	}
} else {
	echo '<br /><div align="center">Área Restrita.<br /><br /><a href="javascript:window.close()">Fechar janela</a></div>';
}
?>
