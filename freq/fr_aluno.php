<?php
/****************************************************************
/ Programador: Claudia Akemi Izeki
/ Originalmente programado por Adriane Kaori Oshiro
/ Data de criação: 17 de Dezembro de 2002
/****************************************************************/

//echo 'curso: '.$curso_id.' id:'.$id;
include "functions.php";
$list = array();
$list = getAulasOfCurso($curso_id);
$n = count($list);

$listaluno = array();
$listaluno = getAttrAluno($id);
$na = count($listaluno);

//echo "<br>n: $n <br>na: $na<br>";

$listfreq = array();

$nome = $listaluno["nome"];
//echo 'nome:'.$nome;
echo "<html><head><title>Lista de Freqüência - $nome</title></head><body>\n";
if (($n == 0) | ($na == 0))
{
echo "<br><font face=Verdana size=2>Uma das listas está vazia...</font>\n";
}
else
{ 

	echo "<table width=600 border=0 cellspacing=0 cellpadding=0>\n";
	echo "<tr><td>\n";

        if ($id != "") {
		
	  $listfreq = freq_aluno($id, $curso_id);
	  $n_freq = count($listfreq);
        echo "<font face=Verdana size=2><b>Nome: </b>\n";
        echo $listaluno["nome"];
        echo "<br><b>Nro USP: </b>\n";
        echo $listaluno["nousp"];
	echo "</font><br><br><table width=600 border=0 cellspacing=1 cellpadding=2 bgcolor=#BFDFFF>\n";
	echo "<tr>\n";
 	echo "<td bgcolor=#BFDFFF><font face=Verdana size=1><b>Aula</b></font></td>\n";
        for ($i=1; $i<17; $i++)
	{echo "<td bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>$i</font></td>\n";} 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=#BFDFFF><font face=Verdana size=1><b>Presença</b></font></td>\n";
        for ($i=0; $i<16; $i++)
	{
         if ($listfreq[$i] == 'yes')
         echo "<td bgcolor=#FFFFFF><p align=center><img src=/coweb/imagem/useron.png alt=presente></td>\n";
	 else if ($listfreq[$i] == 'no')
         echo "<td bgcolor=#FFFFFF><p align=center><img src=/coweb/imagem/useroff.png alt=ausente></td>\n";
         else
         echo "<td bgcolor=#FFFFFF></td>\n";
        } 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=#BFDFFF><font face=Verdana size=1><b>Aula</b></font></td>\n";
        for ($i=17; $i<33; $i++)
	{echo "<td bgcolor=#DDEEFF><p align=center><font face=Verdana size=2>$i</font></td>\n";} 
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=#BFDFFF><font face=Verdana size=1><b>Presença</b></font></td>\n";
      for ($i=16; $i<32; $i++)
	{
       if ($listfreq[$i] == 'yes')
         echo "<td bgcolor=#FFFFFF><p align=center><img src=/coweb/imagem/useron.png alt=presente></td>\n";
	 else if ($listfreq[$i] == 'no')
         echo "<td bgcolor=#FFFFFF><p align=center><img src=/coewb/imagem/useroff.png alt=ausente></td>\n";
         else
         echo "<td bgcolor=#FFFFFF></td>\n";
      } 
	echo "</tr>\n";
	echo "</table>\n";
	} 
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

echo "</body></html>\n";

?>
