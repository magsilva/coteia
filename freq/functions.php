<?php
/****************************************************************
/ Programador: Claudia Akemi Izeki
/ Data de criação: 17 de Dezembro de 2002
/ Algumas dessas funcoes foram baseadas nas implementadas por Adriane Kaori Oshiro.
/ Descrição: Script que possui várias funções úteis.
/****************************************************************/


include_once( "../config.php.inc" );

// responsável pela conexão com a BD
function dbConnection(){
  	global $conexao_id;

    $conexao_id = mysql_pconnect($ce_dbhost, $ce_dbuser, $ce_dbpword) or die("Não foi possível conectar ao servidor MySql");
	
      mysql_select_db($ce_dbname, $conexao_id) or die("Não foi possível selecionar o banco de dados");

    //echo "conexao_id: $conexao_id";
} // end dbConnection

// Autentica senha de algum professor do curso
function autentica($curso_id, $senha){
        dbConnection();
        $comando_sql = "select senha from profMinistraCurso as m, professor as p where m.curso_id=$curso_id and m.prof_id=p.user_id and 
senha=password('$senha')";
        //echo $comando_sql;
        $res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
		return TRUE;
	}
	return FALSE;
} // end autentica


// Retorna o número de aulas de um dado curso
function numDeAulas($curso_id){
	dbConnection();
	$comando_sql = "select count(*) from aula_coteia where curso_id=$curso_id";
	//echo $comando_sql;
	$res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
		$aula = mysql_fetch_array($res);
		$n = $aula[0];
		mysql_free_result($res);
	}
	return $n;
} // end numDeAulas

// Retorna os atributos (nome, sigla e semestre) de um dado curso
function getAttrCurso($curso_id){
	dbConnection();
	$comando_sql = "select nome, sigla, semestre from curso where curso_id=$curso_id";
	//echo $comando_sql;
	$res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
      	while ($curso = mysql_fetch_array($res)) {
                  $curso_attr["nome"] = $curso["nome"];
			$curso_attr["sigla"] = $curso["sigla"];
			$curso_attr["semestre"] = $curso["semestre"];
            }
		mysql_free_result($res);
	}
	return $curso_attr;
} // end getAttrCurso

// Retorna os nomes de professores que ministram um dado curso
function getProfsOfCurso($curso_id){
	dbConnection();
	$comando_sql = "select p.nome 
                      from professor as p, profMinistraCurso as m 
                      where m.prof_id=p.user_id and m.curso_id=$curso_id";
	//echo $comando_sql;
	$res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
		$i = 0;
      	while ($prof = mysql_fetch_array($res)) {
                  $prof_list[$i]["nome"] = $prof[0];
			$i++;
            }
		mysql_free_result($res);
	}
	return $prof_list;
} // end getProfsOfCurso

// Retorna os alunos que cursam um dado curso
function getAlunosOfCurso($curso_id){
	dbConnection();
	$comando_sql = "select a.nome, a.aluno_id, a.nusp
                      from aluno as a, cursa as c 
                      where c.curso_id=$curso_id and c.aluno_id=a.aluno_id order by a.nome";
	//echo $comando_sql;
	$res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
		$i = 0;
      	while ($aluno = mysql_fetch_array($res)) {
                  $aluno_list[$i]["nome"] = $aluno[0];
                  $aluno_list[$i]["login"] = $aluno[1];
                  $aluno_list[$i]["nousp"] = $aluno[2];
			$i++;
            }
		mysql_free_result($res);
	}
	return $aluno_list;
} // end getAlunosOfCurso

// Adiciona uma nova aula. 
// Retorna o id da aula criada, FALSE caso contrário
function addAula($curso_id, $title, $dataAtual){
	dbConnection();
	$comando_sql = "INSERT INTO aula_coteia VALUES (NULL, $curso_id, '$title', '$dataAtual')";
      //echo $comando_sql;
	mysql_query($comando_sql);

      return mysql_insert_id($GLOBALS["conexao_id"]);
} // end addAula

// Adiciona uma tupla na tabela 'assiste'
function addAssiste($aula_id, $login, $pres){
	dbConnection();
	$comando_sql = "INSERT INTO assiste VALUES ($aula_id, '$login', '$pres')";
      //echo $comando_sql;
	mysql_query($comando_sql);

} // end addAssiste


function getAulasOfCurso($curso_id){
	dbConnection();
      $i = 0;
      $query = "SELECT w.aula_id, w.aluno_id, w.assistiu 
                FROM assiste as w, aula_coteia as a
                WHERE a.curso_id=$curso_id and a.aula_id=w.aula_id order by w.aula_id";
	//echo $query;
      $query_res = mysql_query($query);
      if ($query_res) {
                while ($aula = mysql_fetch_array($query_res)) {
                        $aula_list[$i]["aulaid"] = $aula[0];
                        $aula_list[$i]["alunoid"] = $aula[1];
			      $aula_list[$i]["assistiu"] = $aula[2];
                        $i++;
                }
                mysql_free_result($query_res);
	}
	return $aula_list;
}

function getAulasDistinctOfCurso($curso_id){
        dbConnection();
      $i = 0;
      $query = "SELECT DISTINCT w.aula_id
                FROM assiste as w, aula_coteia as a
                WHERE a.curso_id=$curso_id and a.aula_id=w.aula_id order by w.aula_id";
        //echo $query;
      $query_res = mysql_query($query);
      if ($query_res) {
                while ($aula = mysql_fetch_array($query_res)) {
                        $aula_list[$i]["aulaid"] = $aula[0];
                        $aula_list[$i]["alunoid"] = $aula[1];
                              $aula_list[$i]["assistiu"] = $aula[2];
                        $i++;
                }
                mysql_free_result($query_res);
        }
        return $aula_list;
}

function  atualiza_chamada($aula_id, $aluno_id){
        dbConnection();
	$comando_sql = "select assistiu from assiste where aula_id=$aula_id and aluno_id='$aluno_id'";
	//echo $comando_sql;
        $query_res = mysql_query($comando_sql);
        if (mysql_num_rows($query_res)) {
                if ($aula = mysql_fetch_array($query_res)) {
                        $assistiu = $aula[0];
                }
		if ($assistiu == "yes")
			$assistiu = "no";
		else
			$assistiu = "yes";
		$comando_sql = "UPDATE assiste SET assistiu = '$assistiu' WHERE aula_id = $aula_id and aluno_id='$aluno_id'";
		//echo $comando_sql;
		$query_res = mysql_query($comando_sql);
        }

	
} // end atualiza_chamada

function get_presentes($aula_id){
	dbConnection();
        $i = 0;
        $query = "SELECT a.nome, a.aluno_id 
                  FROM aluno as a, assiste as w
                  WHERE w.assistiu = 'yes' AND w.aula_id = $aula_id AND a.aluno_id = w.aluno_id ORDER BY nome";
        //echo $query;
        $query_res = mysql_query($query);
        if ($query_res) {
                while ($pres = mysql_fetch_array($query_res)) {
                       $pres_list[$i]["nome"] = $pres[0];
                       $pres_list[$i]["login"] = $pres[1];
		       $i++;
                }
                mysql_free_result($query_res);
         
	    }
	return $pres_list;	  
}

function get_ausentes($aula_id){
        dbConnection();
        $i = 0;
        $query = "SELECT a.nome, a.aluno_id 
                  FROM aluno as a, assiste as s 
                  WHERE 
s.assistiu = 'no' AND s.aula_id = $aula_id AND a.aluno_id = s.aluno_id ORDER BY a.nome";
        $query_res = mysql_query($query);
        if ($query_res) {
                while ($aus = mysql_fetch_array($query_res)) {
                       $aus_list[$i]["nome"] = $aus[0];
                       $aus_list[$i]["login"] = $aus[1];
		       $i++;
                }
                mysql_free_result($query_res);
	}
	return $aus_list; 	
}

function calcula_frequencia($t, $curso_id){
	dbConnection();
        $i = 0;
        $query = "SELECT w.aluno_id, w.assistiu, COUNT(*) 
                  FROM assiste as w, aula_coteia as a 
                 WHERE w.assistiu = 'yes' and w.aula_id=a.aula_id and a.curso_id=$curso_id GROUP BY w.aluno_id";
        //echo $query;
        $query_res = mysql_query($query);
        if ($query_res) {
                while ($freq = mysql_fetch_array($query_res)) {
                        $freq_list[$i]["alunoid"] = $freq["aluno_id"];
                        $freq_list[$i]["nroaulas"] = $freq["COUNT(*)"];
			$perc = round(($freq_list[$i]["nroaulas"]/$t)*100, 1);
			$freq_list[$i]["freqtotal"] = $perc;
                        $i++;
                }
                mysql_free_result($query_res);
	}
	return $freq_list;
}

function freq_aluno($id, $curso_id){
        dbConnection();
        $i = 0;
        $query = "SELECT w.aula_id, w.aluno_id, w.assistiu 
                  FROM assiste as w, aula_coteia as a
                  WHERE w.aluno_id = '$id' and w.aula_id=a.aula_id and a.curso_id=$curso_id ORDER by aula_id";
        //echo $query;
        $query_res = mysql_query($query);
        if ($query_res) {
                while ($freq = mysql_fetch_array($query_res)) {
                  $freq_list[$i] = $freq["assistiu"];
                  $i++;
                }
                mysql_free_result($query_res);
          
        } 
        return $freq_list;
}

// Retorna os atributos de um dado aluno
function getAttrAluno($id){
	dbConnection();
	$comando_sql = "select nome, aluno_id, nusp from aluno where aluno_id='$id'";
	//echo $comando_sql;
	$res = mysql_query($comando_sql);
	if (mysql_num_rows($res)){
      	while ($curso = mysql_fetch_array($res)) {
                  $curso_attr["nome"] = $curso["nome"];
			$curso_attr["login"] = $curso["aluno_id"];
			$curso_attr["nousp"] = $curso["nusp"];
            }
		mysql_free_result($res);
	}
	return $curso_attr;
} // end getAttrCurso

function getTitleAula($id){
        dbConnection();
      $i = 0;
      $query = "SELECT titulo
                FROM aula_coteia 
                WHERE aula_id=$id";
        //echo $query;
      $query_res = mysql_query($query);
      if ($query_res) {
                if ($aula = mysql_fetch_array($query_res)) {
                        $title = $aula[0];
                }
                mysql_free_result($query_res);
        }
        return $title;
}

  function convert_date($date)
  {
    $array_temp = explode("-", $date);
    $vetor_data[0] = $array_temp[2];
    $vetor_data[1] = $array_temp[1];
    $vetor_data[2] = $array_temp[0];

    $str = "$vetor_data[0]-$vetor_data[1]-$vetor_data[2] ";

    return $str;
  } // fim function convert_date
?>
