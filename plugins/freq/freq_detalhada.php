<?php

/****************************************************************
/ Programador: Claudia Akemi Izeki
/ Data de criação: 19 de Dezembro de 2002
/ Originalmente implementado por Adriane Kaori Oshiro
/****************************************************************/
//$curso_id = 4;

include_once( "functions.php" );
$numDeAulas = numDeAulas($curso_id);

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


echo "<html><head><title>Lista de Freqüência Aula a Aula</title></head><body>\n";
echo "<font size=2 face=Verdana>    
<a href='$URL_COWEB/freq/chamada.php?curso_id=$curso_id'><b>Fazer 
chamada</b></a>&nbsp;&nbsp;&nbsp;            
<a href='$URL_COWEB/freq/freq.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia 
Total</b></a>&nbsp;&nbsp;&nbsp;            
<a href='$URL_COWEB/freq/freq_detalhada.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia Aula a            
Aula</b></a>
<br><br>";
echo "<font face=Verdana size=3 color=#0066CC><b>Lista de Freqüência Aula a Aula</b><br></font>\n";
echo "<font face=Verdana size=2><b>$cursoSigla - $cursoNome - 
$cursoSemestre</b></font><br>\n";
echo "<font face=Verdana size=2>";
for ( $i=0; $i<count($arrayProfs); $i++ ){
	echo '<b>'.$arrayProfs[$i]["nome"].'</b><br>';
}
echo "</font><br><br>\n";

echo "<font face=Verdana size=2><b>Total de aulas: </b></font><font face=Verdana size=3 color=#CC0000><b>$numDeAulas</b></font><br><br>\n";

$list = array();
$listAulas = array();
$listAulas = getAulasDistinctOfCurso($curso_id);

  if ($mode == "send"){
	if (autentica($curso_id, $senha)){
	   $alu = explode("-", $ids_alunos);

  	   while (current($alu)){
    		$id = current($alu);
    		//echo "$id<br>";
    		if ($id != "Alterar")
    			atualiza_chamada($listAulas[$aula-1]["aulaid"], $id);
		next($alu);
  	   } // end while
	}
	else{
		echo "<center><P>senha invalida!</P><A HREF='javascript:history.back()'>Voltar</A></center>";
		exit();
	}
  } // end if


$list = getAulasOfCurso($curso_id);

$n = count($list);
//echo "<br>n: $n <br>";
$listaluno = array();
$listaluno = getAlunosOfCurso($curso_id);
$na = count($listaluno);
//echo "na: $na <br>";

$listfreq = array();

for ($i=0; $i<$numDeAulas; $i++)
{
$p=0;
$a=0;
$min=($i*$na);
$max=(($i*$na)+$na);
	for ($j=$min; $j<$max; $j++)
	{
         if ($list[$j]["assistiu"] == 'yes') {$p++;}
	 else {$a++;} 
	}
$listfreq[$i]["pres"] = $p;
//echo 'OI: '.$listfreq[$i]["pres"].'<br>';
$listfreq[$i]["aus"] = $a;
$listfreq[$i]["title"] = getTitleAula($listAulas[$i]["aulaid"]); 
//echo 'OLA: '.$i.$listfreq[$i]["title"].'<br>';
}

// calcula media de frequencia
$media=0;
for ($i=0; $i<$numDeAulas; $i++)
{
$media = $media + $listfreq[$i]["pres"];
}
if ($numDeAulas>0){
$media = $media/$numDeAulas;
$media = round(($media*100)/$na,1);
}
if (($n == 0) | ($na == 0))
{
echo "<br><font face=Verdana size=2>Uma das listas está vazia...</font>\n";
}
else
{ 
if (!$aula) {$aula = $numDeAulas-1;}
else {$aula--;}
//echo '<br>aula:'.$aula.'<br>'.$listAulas[$aula]["aulaid"];
	$alunos_yes = array();
	$alunos_yes = get_presentes ($listAulas[$aula]["aulaid"]);
	$n_pres = count($alunos_yes);
	$alunos_no = array();
	$alunos_no = get_ausentes ($listAulas[$aula]["aulaid"]);
	$n_aus = count ($alunos_no);

	echo "<form name=lista action=$PHP_SELF method=post>\n";
	echo "<table width=600 border=0 cellspacing=0 cellpadding=0>\n";
	echo "<tr><td width=70%><font face=Verdana size=2>Selecione o número da aula para ver a lista de freqüência:</font></td>\n";
	echo "<td width=10%><p align=center><select name=aula>\n";
	if ($numDeAulas==0)
	echo "<option value=0 selected>(Sem Aulas)</option>\n";
	else
	if ($aula == $numDeAulas-1) {echo "<option value=".$numDeAulas; echo " selected>$numDeAulas</option>\n";}
	else {echo "<option value=".$numDeAulas; echo ">$numDeAulas</option>\n";} 
	for ($i=$numDeAulas-1; $i>0; $i--){
		echo "<option value=".$i;
		if (($aula+1)==$i){
		echo " selected>";
		} 
		else{echo ">";}
		echo $i."</option>\n";
	}
        echo "</select></td>\n"; 
	echo "<td width=20%><input type=submit value=\" OK \">\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<INPUT TYPE=hidden NAME=curso_id VALUE=$curso_id>\n";
	echo "</form>\n";

	echo "<table width=600 border=0 cellspacing=0 cellpadding=0>\n";
	echo "<tr valign=top><td width=280>\n"; 

	echo "<table width=270 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
	echo "<tr><td width=47%><font face=Verdana size=2><p align=center><b>Aula</b></font></td>\n";
	echo "<td width=26.5%><font face=Verdana size=2><p align=center><b>Presentes</b></font></td>\n";
	echo "<td width=26.5%><font face=Verdana size=2><p align=center><b>Ausentes</b></font></td></tr>\n";
	for ($i=$numDeAulas-1; $i>=0; $i--)
	{
	 if ($i == $aula) {	
	 echo "<tr><td width=47% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2 color=#CC0000>>> </font><font face=Verdana size=2>\n";
	 echo ($i+1); echo ' - '.$listfreq[$i]["title"]; echo "</font><font face=Verdana size=1 color=#CC0000> </font></td>\n";
	 echo "<td width=26.5% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>\n";
	 echo $listfreq[$i]["pres"]; echo "</font></td>\n";
	 echo "<td width=26.5% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>\n"; 
	 echo $listfreq[$i]["aus"]; echo "</font></td></tr>\n"; 
	 }
	 else {
	 echo "<tr><td width=47% bgcolor=#FFFFFF><p align=center><font face=Verdana size=1>\n";
	 echo ($i+1); echo ' - '.$listfreq[$i]["title"]; echo "</font></td>\n";
	 echo "<td width=26.5% bgcolor=#FFFFFF><p align=center><font face=Verdana size=2>\n";
	 echo $listfreq[$i]["pres"]; echo "</font></td>\n";
	 echo "<td width=26.5% bgcolor=#FFFFFF><p align=center><font face=Verdana size=2>\n"; 
	 echo $listfreq[$i]["aus"]; echo "</font></td></tr>\n"; 
         }
	}
	echo "</table>\n";

	echo "<br><table width=270 border=0 cellspacing=0 cellpadding=0>\n";
	echo "<tr><td><p align=justify>\n";	
	echo "<font face=Verdana size=3 color=#CC0000><b>$media%</b></font><font face=Verdana size=2> dos alunos comparecem às aulas, em média.</font> ";
	echo "</td></tr></table>\n";
	
	echo"</td>\n";

	echo "<td width=320>\n";

	echo "<form name=alunos method=post action=''>\n";

	echo "<table width=310 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
	echo "<tr><td><font face=Verdana size=2><b>Alunos ausentes</b></font></td></tr>\n";
	if ($n_aus > 0){
	for ($i=0; $i<$n_aus; $i++) {
		echo "<tr><td bgcolor=#FFFFFF><font face=Verdana size=2>\n";
		$login_aluno = $alunos_no[$i]["login"];
		echo "<input type='checkbox' name='check_pres[$login_aluno]' value='$login_aluno'>"; 
		echo $alunos_no[$i]["nome"];
		echo "</font></td></tr>\n";
		}
	}
	else {echo "<tr><td bgcolor=#FFFFFF><font face=Verdana size=2>Nenhum aluno ausente.</font></td></tr>\n";}

	echo "</table>\n";
	echo "<table width=310 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
	echo "<tr><td><font face=Verdana size=2><b>Alunos presentes</b></font></td></tr>\n"; 

	if ($n_pres > 0){
	for ($i=0; $i<$n_pres; $i++) {
		echo "<tr><td bgcolor=#FFFFFF><font face=Verdana size=2>\n";
		$login_aluno = $alunos_yes[$i]["login"];
		echo "<input type='checkbox' name='check_aus[$login_aluno]' value='$login_aluno'>"; 
		echo $alunos_yes[$i]["nome"];
		echo "</font></td></tr>\n";
		}
	}
	else {echo "<tr><td bgcolor=#FFFFFF><font face=Verdana size=2>Nenhum aluno presente.</font></td></tr>\n";}
	echo "<tr><td><font face=Verdana size=2>senha: </font><input type=password name=senha size=5><input type='button' name = 'botao' 
value='Alterar' OnClick='return verifica()'></td></tr>";

	echo "</table>\n";


	echo "</form>";

	echo"</td></tr>\n";

	echo "</table>\n";
}

?>

</body>
<script language="Javascript">
<!--
function verifica()
{
    if (document.alunos.senha.value == ""){
          alert('Campo de senha vazio!');
          return false;
    }

  if (AnySelected()) {
    var msg;

    len = document.alunos.elements.length;
    var i;
    nome = "freq_detalhada.php?curso_id=<?php echo $curso_id; ?>&aula=<?php echo $aula+1; ?>&mode=send&ids_alunos=";
    for (i = 0; i < len; i++) {
      if (document.alunos.elements[i].checked)
        nome = nome + document.alunos.elements[i].value + "-";
    }
    document.alunos.action=nome;

    document.alunos.submit();
  }
  else
    window.alert('Tem que selecionar pelo menos um aluno!');
} // fim function Submit

function AnySelected()
{
  len = document.alunos.elements.length;
  var i;
  for (i = 0; i < len; i++) {
    if (document.alunos.elements[i].checked)
    return true;
  }
  return false;
} // fim function AnySelected
	
// -->
</script>

</html>
