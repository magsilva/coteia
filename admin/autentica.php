<?
/*
 * NOME:                autentica
 * DESCRICAO:           Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:        $usuario - usuario & $passwd - senha 
 * PAR. SAIDA:          --
 * RETORNO:             TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:         --
 */       
 
include_once("function.inc");

$retorno = autentica( $usuario, $passwd  );
if ( $retorno ) {
	$sess = new coweb_session;
	$sess->start(900);
	$sess->register($usuario);
	header("Location:main.php"); //Redireciona para a interface inicial
} else {
	header("Location:index.php"); //Redireciona para interface de login
}
exit;
?>
