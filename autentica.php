<?
/*
 * NOME:                autentica
 * DESCRICAO:           Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:        $usuario - usuario & $passwd - senha 
 * PAR. SAIDA:          --
 * RETORNO:             TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:         --
 */       
 
    include("function.inc");

    $dbh = db_connect();

    $retorno = login_swiki($usuario,$passwd,$id,$dbh);

    if ($retorno) {

	if ($token== '1')  {
                header("Location:ok.php?id=$id"); //Redireciona para a interface inicial
                exit;
	}
	else if ($token == '0') {
                header("Location:login_create.php?id=$id&index=$index");
                exit;
	}

    }
?>
