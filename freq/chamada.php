<?php
/****************************************************************
/ Programador: Claudia Akemi Izeki
/ Originalmente programado por Adriane Kaori Oshiro
/ Data de criação: 17 de Dezembro de 2002
/ Descrição: Script que manipula chamada de alunos de um dado curso
/ numa dada aula
/****************************************************************/

include "functions.php";

//$curso_id = 4;
// 1 - Verifique quantas aulas do dado curso foram cadastradas.
//select count(*) from aula where curso_id=$curso_id;
$numDeAulas = numDeAulas($curso_id);
if ( $numDeAulas == 0)
	$numDaAula = 1;
else
	$numDaAula = $numDeAulas + 1;
// 2 - Verifique os dados (nome, sigla e semestre) do curso dado o seu id.
//select nome, sigla, semestre from curso where curso_id=$curso_id;
$arrayCurso = getAttrCurso($curso_id);
$cursoNome = $arrayCurso["nome"];
$cursoSigla = $arrayCurso["sigla"];
$cursoSemestre = $arrayCurso["semestre"];

// 3 - Verifique os professores que ministram o dado curso.
//select p.nome from professor as p, profMinistraCurso as m where m.prof_id
//=p.user_id and m.curso_id=$curso_id;
$arrayProfs = getProfsOfCurso($curso_id);

// 4 - Verifique a data atual
$dataAtual = date("d-m-Y");

// 5 - Verifique os alunos do dado curso
//select a.nome, a.aluno_id from aluno as a, cursa as c where c.curso_id=4
//and c.aluno_id=a.aluno_id;
$arrayAlunos = getAlunosOfCurso($curso_id);

if (!isset($mode)) {
  $mode = 'main';
}
//echo "<br>MODE:$mode<br>senha:$senha<br>";
// Início do switch
if ($mode=='main') {
///// Case 'main'

echo "<html><head><title>Chamada Eletrônica - $cursoSigla $cursoSemestre</title></head><body>\n";
echo "<font size=2 face=Verdana>
<a href='http://coweb.icmc.usp.br/coweb/freq/chamada.php?curso_id=$curso_id'><b>Fazer 
chamada</b></a>&nbsp;&nbsp;&nbsp;
<a href='http://coweb.icmc.usp.br/coweb/freq/freq.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia 
Total</b></a>&nbsp;&nbsp;&nbsp;
<a href='http://coweb.icmc.usp.br/coweb/freq/freq_detalhada.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia Aula a
Aula</b></a>
<br><br>";

echo "<font face=Verdana size=3 color=#0066CC><b>Chamada Eletrônica</b></font><br>\n";
echo "<font face=Verdana size=2><b>$cursoSigla - $cursoNome - 
$cursoSemestre</b></font><br>\n";
echo "<font face=Verdana size=2>";
for ( $i=0; $i<count($arrayProfs); $i++ ){
	echo $arrayProfs[$i]["nome"].'<br>';
}
echo "</font><br><br>\n";
echo "<form name=chamada action=''  method=post >\n";
echo "<font face=Verdana size=3 color=#CC0000><b>Aula $numDaAula:</b> <input type='text' name='title' size=80></font><br>\n";
echo "<font face=Verdana size=2>Data: <input type='text' name='data' value = '$dataAtual' size=10> <I>formato (dd-mm-aaaa)</I></font><br><br>\n";
echo "<font face=Verdana size=2><input type=button value=\" Todos presentes \" onClick='return marca_todos_presentes(document.chamada)'><input type=button value=\" Todos ausentes \" onClick='return marca_todos_ausentes(document.chamada)'></font><br><br>\n";
echo "<table width=600 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
echo "<tr>\n";
echo "<td width=15%><p align=center><font face=Verdana size=2><b>Presente</b></font></td>\n";
echo "<td width=15%><p align=center><font face=Verdana size=2><b>Ausente</b></font></td>\n";
echo "<td width=55%><font face=Verdana size=2><b>ID - Nome</font></b></td>\n";
echo "<td width=15%><font face=Verdana size=2><b>Nro USP</b></font></td>\n";
echo "</tr>\n";

$numDeAlunos = count($arrayAlunos);
if ( $numDeAlunos== 0)
	echo "</table><br><font face=Verdana size=2>Não há alunos cadastrados.</font>\n";
else{
	$cor = 0;
	for ( $i=0; $i < $numDeAlunos; $i++ ){ // o código de intercalação de cores foi reutilizado da Adriane Kaori
		$login = $arrayAlunos[$i]["login"];
		echo "<tr>\n";
		if ($cor == 0){
			echo "<td width=15% bgcolor=#DDEEFF><p align=center><input type=radio name=presenca$i value=1_$login 
CHECKED></td>\n";
			echo "<td width=15% bgcolor=#DDEEFF><p align=center><input type=radio name=presenca$i value=0_$login></td>\n";
			echo "<td width=55% bgcolor=#DDEEFF><font face=Verdana size=2>\n";
		   	echo $login;
  	            echo " - ";
		      echo $arrayAlunos[$i]["nome"];
                  echo "</font></td>\n";
  	            echo "<td width=15% bgcolor=#DDEEFF><font face=Verdana size=2>\n";
                  echo $arrayAlunos[$i]["nousp"];
                  echo "</font></td>\n";
        	      $cor++;
	      }
 	      else{
			echo "<td width=15% bgcolor=#FFFFFF><p align=center><input type=radio name=presenca$i value=1_$login 
CHECKED></td>\n";
			echo "<td width=15% bgcolor=#FFFFFF><p align=center><input type=radio name=presenca$i value=0_$login></td>\n";
			echo "<td width=55% bgcolor=#FFFFFF><font face=Verdana size=2>\n";
		      echo $login;
		      echo " - ";
		      echo $arrayAlunos[$i]["nome"];
		      echo "</font></td>\n";
			echo "<td width=15% bgcolor=#FFFFFF><font face=Verdana size=2>\n";
		      echo $arrayAlunos[$i]["nousp"];
		      echo "</font></td>\n";
			$cor--;
      	}
		echo "</tr>\n";
	} // end for
} // end else
 
echo "<tr>\n";
echo "<td width=15% height=15></td>\n";
echo "<td width=15% height=15></td>\n";
echo "<td width=55% height=15></td>\n";
echo "<td width=15% height=15></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=15%><p align=right><font face=Verdana size=2>senha: </font></td>\n";
echo "<td width=15%><input type=password name=senha size=5></td>\n";
echo "<td width=55%><input type=button value=\" Enviar \" onClick='return verifica_campos(document.chamada)'></td>\n";
echo "<td width=15%></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<INPUT TYPE=hidden NAME=numDeAlunos VALUE=$numDeAlunos>\n";
echo "<INPUT TYPE=hidden NAME=curso_id VALUE=$curso_id>\n";
echo "</form>\n";

} // end if main
///// Case 'send'
else if ($mode == "send"){

if (autentica($curso_id, $senha)){
	$alunos = explode("-", $ids_alunos);
//	echo "titulo: $title";
	if (trim($title) == "")
		$title = 'Aula'.$numDaAula;
	//$data = date("Y-m-d");
	//$data = "2002-12-22";
	//echo "data: $data";
	$aula_id = addAula($curso_id, $title, convert_date($data));
	if ($aula_id){
	 	while (current($alunos)){
		   	$aluno = current($alunos);
			$aux = explode("_", $aluno);
			$pres = $aux[0];
			$login = $aux[1];
			//echo "<li/>$aluno - $pres - $login";
			if ($pres == 1)
				$pres = "yes";
			else $pres = "no";
			addAssiste($aula_id, $login, $pres);
			next($alunos);
		} // end while
	} // end if $aula_id


echo "<html><head><title>Chamada Eletrônica - $cursoSigla $cursoSemestre </title></head><body>\n";
echo "<font size=2 face=Verdana>    
<a href='http://coweb.icmc.usp.br/coweb/freq/chamada.php?curso_id=$curso_id'><b>Fazer chamada</b></a>            
<a href='http://coweb.icmc.usp.br/coweb/freq/freq.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia Total</b></a>            
<a href='http://coweb.icmc.usp.br/coweb/freq/freq_detalhada.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia Aula a            
Aula</b></a>
<br><br>";

echo "<font face=Verdana size=3>Operação realizada com sucesso!</font><br><br>\n";

echo "<font face=Verdana size=3><a href=freq_detalhada.php?curso_id=$curso_id>Verificar a Lista de Presença</a></font><br><br>\n";
echo "</body></html>\n";
} 
else {
echo "<html><head><title>Chamada Eletrônica - $cursoSigla $cursoSemestre </title></head><body>\n";
echo "<P><CENTER><font face=Verdana size=3>Senha Incorreta!</font></P><A 
HREF='javascript:history.back()'>Voltar</A><br><br></CENTER>\n";
echo "</body></html>\n";
}
}// end Case 'send'

?>

<script language="Javascript">
<!--

function verifica_campos(chamada)
{
    if (chamada.senha.value == ""){
	  alert('Campo de senha vazio!');
	  return false;
    }
    len = document.chamada.elements.length;
    var i;
    nome = "chamada.php?mode=send&ids_alunos=";
    for (i = 0; i < len; i++) {
      if (document.chamada.elements[i].checked){
        nome = nome + document.chamada.elements[i].value + "-";
	}
    }
    document.chamada.action=nome;
    document.chamada.submit();
}

function marca_todos_presentes(chamada)
{
    len = document.chamada.elements.length;
    var i;
    var j = 0;
	for (i = 0; i < len; i++){
	      if ((document.chamada.elements[i].type == "radio") && ((i%2) == 0)){
      		document.chamada.elements[i].checked = true;
		}
	}
}

function marca_todos_ausentes(chamada)
{
    len = document.chamada.elements.length;
    var i;
    var j = 0;
	for (i = 0; i < len; i++){
	      if ((document.chamada.elements[i].type == "radio") && ((i%2) == 1)){
      		document.chamada.elements[i].checked = true;
		}
	}

}
// -->
</script>

</body>
</html>
