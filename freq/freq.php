<?php
/****************************************************************
/ Programador: Claudia Akemi Izeki
/ Originalmente programado por Adriane Kaori Oshiro
/ Data de criação: 17 de Dezembro de 2002
/****************************************************************/

//$curso_id = 4;
include_once( "functions.php" );
$numDeAulas = numDeAulas($curso_id);
$t = $numDeAulas;

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

echo "<html><head><title>Lista de Freqüência</title>\n";
echo "<script language=\"Javascript\">\n";
echo "<!--\n";
echo "function Aluno(id){\n";	
echo "window.open('fr_aluno.php?id='+id+'&curso_id=$curso_id','_blank','toolbar=0,directories=0,location=0,scrollbars=0,menubar=0,status=0,resizable=0,width=622,height=170');\n";
echo "}\n";
echo "//-->\n";
echo "</script>\n</head><body>\n";
echo "<font size=2 face=Verdana>
<a href='$URL_COWEB/freq/chamada.php?curso_id=$curso_id'><b>Fazer 
chamada</b></a>&nbsp;&nbsp;&nbsp;
<a href='$URL_COWEB/freq/freq.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia 
Total</b></a>&nbsp;&nbsp;&nbsp;
<a href='$URL_COWEB/freq/freq_detalhada.php?curso_id=$curso_id'><b>Freq&uuml;&ecirc;ncia Aula a
Aula</b></a>
<br><br>";

echo "<font face=Verdana size=3 color=#0066CC><b>Lista de Freqüência</b><br></font>\n";
echo "<font face=Verdana size=2><b>$cursoSigla -  $cursoNome - 
$cursoSemestre</b></font><br>\n";
echo "<font face=Verdana size=2>";
for ( $i=0; $i<count($arrayProfs); $i++ ){
	echo '<b>'.$arrayProfs[$i]["nome"].'</b><br>';
}
echo "</font><br><br>\n";

echo "<font face=Verdana size=2><b>Total de aulas: </b></font><font face=Verdana size=3 color=#CC0000><b>$t</b></font><br><br>\n";

$list = array();
$list = getAulasOfCurso($curso_id);
$n = count($list);
$listaluno = array();
$listaluno = getAlunosOfCurso($curso_id);
$na = count($listaluno);
$listfreq = array();
$listfreq = calcula_frequencia($t, $curso_id);
$nf = count($listfreq);

//echo "<br>n: $n <br>na: $na<br>nf:$nf";

// acochambreira!! 
$listsort = array();
for ($i=0; $i<$na; $i++)
{
 $achou = 0;
 $j = 0;
 while (($achou == 0) & ($j < $na))
  {

   if ($listaluno[$i]["login"] == $listfreq[$j]["alunoid"])
    {$listsort[$i]["nroaulas"] = $listfreq[$j]["nroaulas"];
     $listsort[$i]["freqtotal"] = $listfreq[$j]["freqtotal"];
     $achou = 1; $j++;
//	echo 'ola: '.$listfreq[$j]["nroaulas"].'<br';
    }
   else {$j++;}
  }
} 

if (($n == 0) | ($na == 0) | ($nf == 0))
{
echo "<br><font face=Verdana size=2>Uma das listas está vazia...</font>\n";
}
else
{ 
	echo "<table width=600 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
	echo "<tr><td width=15%><font face=Verdana size=2><b>Nro USP</b></font></td>\n";
	echo "<td width=42%><font face=Verdana size=2><b>Nome</b></font></td>\n";
	echo "<td width=10%><font face=Verdana size=2><p align=center><b>#Pres.</b></font></td>\n";
	echo "<td width=18%><font face=Verdana size=2><p align=center><b>Freq. Total</b></font></td>\n";
	echo "<td width=15%><font face=Verdana size=2><p align=center><b>Detalh.</b></font></td></tr>\n";
	$cor = 0;
	for ($i=0; $i<$na; $i++)
	{
	 if ($cor == 0) {	
	 echo "<tr><td width=13% bgcolor=#DDEEFF><font face=Verdana size=2>\n";
	 echo $listaluno[$i]["nousp"]; echo "</font></td>\n";
	 echo "<td width=42% bgcolor=#DDEEFF><font face=Verdana size=2>\n";
	 echo $listaluno[$i]["nome"]; echo "</font></td>\n";
	 echo "<td width=10% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>\n";
	 echo $listsort[$i]["nroaulas"]; echo "</font></td>\n";

	 if ($listsort[$i]["freqtotal"] < 70){
	 echo "<td width=18% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2 color=#FF0000>\n"; 
	 echo $listsort[$i]["freqtotal"]; echo "%</font></td>\n"; 
         }
	 else {
	 echo "<td width=18% bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>\n"; 
	 echo $listsort[$i]["freqtotal"]; echo "%</font></td>\n"; 
         }

	 echo "<td width=15% bgcolor=#DDEEFF>\n";
	 $id = $listaluno[$i]["login"];
	 echo "<p align=center><font face=Verdana size=2><a href=\"javaScript:Aluno('$id')\">Mais...</a>\n";
         echo "</font></td></tr>\n";	 
         $cor++;
	 }

	 else {
	 echo "<tr><td width=15% bgcolor=#FFFFFF><font face=Verdana size=2>\n";
	 echo $listaluno[$i]["nousp"]; echo "</font></td>\n";
	 echo "<td width=42% bgcolor=#FFFFFF><font face=Verdana size=2>\n";
	 echo $listaluno[$i]["nome"]; echo "</font></td>\n";
	 echo "<td width=10% bgcolor=#FFFFFF><p align=center><font face=Verdana size=2>\n";
	 echo $listsort[$i]["nroaulas"]; echo "</font></td>\n";

	 if ($listsort[$i]["freqtotal"] < 70){
	 echo "<td width=18% bgcolor=#FFFFFF><p align=center><font face=Verdana size=2 color=#FF0000>\n"; 
	 echo $listsort[$i]["freqtotal"]; echo "%</font></td>\n"; 
         }
	 else {
	 echo "<td width=18% bgcolor=#FFFFFF><p align=center><font face=Verdana size=2>\n"; 
	 echo $listsort[$i]["freqtotal"]; echo "%</font></td>\n"; 
         }

	 echo "<td width=15% bgcolor=#FFFFFF>\n";
	 $id = $listaluno[$i]["login"];
	 echo "<p align=center><font face=Verdana size=2><a href=\"JavaScript:Aluno('$id')\">Mais...</a>\n";
         echo "</font></td></tr>\n";
         $cor--;
        }
	}
	echo "</table>\n";
}

?>


</body>
</html>
