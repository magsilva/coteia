<?php

/*
 * NOME:                session_destroy
 * DESCRICAO:           Finaliza as sessoes que nao puderam ser finalizadas pelo script
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         Deve-se rodar este script para limpar a base de dados das sessoes inativas.
 *                      Para rodar o script: shell> php session_destroy.php 
 */

 include_once("function.inc");

 $dbh = db_connect();

 mysql_select_db($dbname,$dbh);

//busca os valores de ultimo acesso e tempo de expiracao
$query = mysql_query("SELECT access, sec_expire,sess_key FROM sessions") or die("query failed - line 78");

while ($fetch = mysql_fetch_array($query)) {

$access = $fetch["access"];
$expire = $fetch["sec_expire"];
$sess_key = $fetch["sess_key"];

//testa se a sessao expirou
if((time() - $access) >= ($expire)) {

//busca o valor da chave de sessao no cookie
$this = $sess_key;

//apaga a tupla de sessao da base de dados
$query1 = mysql_query("DELETE FROM sessions WHERE sess_key = '" . $this . "'") or die("query failed - line 86");

//remove o cookie do computador do usuario
$delete = setcookie('sess_key' , $this, time()-3600);
}

}

?>
