<?php
/*
* Interface de historico das paginas.
*/
?>

<html>

<head>
	<title>Histórico</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<script type="text/javascript" src="coteia.js"></script>
</head>

<?php
include_once("function.inc");
include_once("cvs/function_cvs.inc");

// Encontra id_swiki
$get_swiki = explode(".",$ident);
$id_swiki = $get_swiki[0];
  
//arquivo a ser feito checkout
$arquivo = $ident . ".html";

//arquivo de log
$arquivo_log = $ident . ".log";

//$comando_login = "cvs -d :pserver:".$username."::@".$host.":".$caminho_repositorio." login";

$ModuloCheckout = "html/"; //se quiser separar conteudos no repositorio (ex. paginas html e arquivos de upload,
                          //pode-se colocar $moduloCheckout = "html" e, depois, = "uploads"...

// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
// Posteriormente ele deve ser excluido.

$caminho = cria_dir($parent);
$caminho .= "/"; 
chdir($caminho);

$comando_checkout = "cvs -d :pserver:".$username."@".$host.":".$caminho_repositorio." co ".$ModuloCheckout.$arquivo;

//exec($comando_login);

exec($comando_checkout);

$caminho_checkout = $caminho.$ModuloCheckout;

chdir($caminho_checkout);

//observacao: esses comandos tambem podem ser executados com popen
//coloca o arquivo salvo (ou do qual foi feito um upload) dentro do diretorio de trabalho

$comando_log = "cvs -d :pserver:".$username."@".$host.":".$caminho_repositorio." log ".$arquivo." > ".$arquivo_log;

//exec($comando_login);

exec($comando_log); //obtem o arquivo de log.

//fazer o parsing do arquivo e gerar a lista de versoes

if (!$fp_log = fopen ($arquivo_log, "r")) {
 print "<html><body><h1>Erro ao abrir o arquivo $arquivo_log ...</h1></body></html>";
}
else { //abriu o arquivo. ler linha a linha ate encontrar "revision"
 while ( !feof($fp_log)) {
  $linha = fgets ($fp_log, 150);
  if (!strncmp($linha, "revision", 8) ) {
   $pos = strpos ($linha, " "); //procura a posicao do espaco
   ++$pos; //agora esta na posicao do primeiro numero da revisao
   $tamanho = strlen ($linha) - $pos - 1; //quantos caracteres possui a informacao com numero
                                      //da versao.
   $revisaoAtual = substr($linha, $pos, $tamanho); //variavel que contem a revisao atual
   $revisao[] = $revisaoAtual;
   //Agora, pegar a data da revisao atual
   $linha = fgets($fp_log, 300);
   $pos = strpos ($linha, " "); //posiciona depois do primeiro espaco (date: )
   ++$pos;
   $tamanho = 10; //numero de digitos da data
   $dataAtual = substr($linha, $pos, $tamanho);

   //arrumar o formato da data
   $subdatas = explode ("/", $dataAtual);
   $dataCerta = array_reverse($subdatas);

   //Agora, pegar o horario da ultima mudanca (OBS.: horario GMT)
   $pos += $tamanho;
   ++$pos;
   $tamanho = 8; //tamanho do horario
   $horarioAtual = substr ($linha, $pos, $tamanho);
   //Arrumar horario para o horario atual (GMT - 2h)
   $subHoras = explode(":", $horarioAtual);
   if ($subHoras[0] == 00 || $subHoras[0] == 01) {
    $subHoras[0] += 22;
    $dataCerta[0] -= 1;
   }
   else {
   $subHoras[0] -= 2; // (tirar 2 horas...)
   }

   $dataAtual = implode ("-",$dataCerta);
   $horarioAtual = implode(":", $subHoras);
   $data[] = $dataAtual;
   $horario[] = $horarioAtual;
   ++$num_revisoes;
  } // fim do if
 } //fim while
} //fim else

apaga_dir($caminho);

?>
<body>
                <A href="mostra.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/view.png" border=0></A>
		<IMG src="<?echo $URL_IMG?>/editbw.png" border=0>
                <A href="history.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/history.png" border=0></A>
		<A href="mostra.php?ident=<?echo $id_swiki?>">
		<IMG src="<?echo $URL_IMG?>/indice.png" border=0></A>
		<A href="JavaScript:AbreMapa(<?echo $id_swiki?>)">
	        <img src="<?echo $URL_IMG?>/map.png" border="0"/></A>
		<A href="changes.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/changes.png" border=0></A>
                <A href="upload.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/upload.png" border=0></A>
		<A href="search.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/search.png" border=0></A>
		<A href="help.php">
		<IMG src="<?echo $URL_IMG?>/help.png" border=0></A>
		<A href="JavaScript:AbreChat(<?echo $id_swiki?>)">
		<img src="<?echo $URL_IMG?>/chat.png" border="0"/></A>
		<img src="<?echo $URL_IMG?>/notebw.png" border="0"/>
		<A href="JavaScript:Imprime()">
		<img src="<?echo $URL_IMG?>/print.png" border="0"/></A>
<br><br>
<!-- geracao da form -->
<form METHOD=POST ACTION="getrevisao_cvs.php">

	<!-- check box para o usuario ter opcao de comparar com versao original ou nao -->
	<INPUT TYPE="checkbox" name="compara" value="1" CHECKED> Comparar com a versao atual<br>
	<select NAME="revisao">
        <OPTION VALUE="0" SELECTED>Histórico</OPTION>

<?php
 for ($i=0; $i<$num_revisoes; $i++) {
  print "<option value=\"$revisao[$i]\">Em $data[$i] $horario[$i]</option>\n";
 }  
?>

</select>
<INPUT TYPE="hidden" NAME="ident" VALUE="<?echo $ident;?>">
<input TYPE="submit" NAME="submit_btn" VALUE="submit">
</form>
</body>
</html>
