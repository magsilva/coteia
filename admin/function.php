<?php
include_once("function.inc");
$sess = new coweb_session;
$sess->read();
	
/* status das swikis:
0 = padrao (sem login e sem controle de concorrencia)
1 = login 

Parametro:
1 = adiciona swiki
2 = remove swiki
3 = atualiza swiki
*/

if ( isset( $parametro ) ) {
	$dbh = db_connect();

	# seleciona base de dados
	mysql_select_db($dbname,$dbh);

	if ( $parametro == "1" ) {		
		$d = getdate();
		$data = $d["year"] . "-" . $d["mon"] . "-" . $d["mday"] . " " . $d["hours"] . ":" . $d["minutes"] . ":" . $d["seconds"];

		/* Set up the chat session
		 You need to set the session name (any string) - $session_name
		 and the moderator's name (session's owner) - $moderator (it can be an empty string: "")
		*/
		$dbh_chat = cs_db_pconnect();
		$session_id = cs_session_create( $nem_swiki, $sess_val, $dbh_chat );

		/* Set up the annotation */
		$folder_id = create_folder( $new_swiki, 0, 14, 9, 255 );

		$query = "insert into swiki (id,status,visivel,titulo,log_adm,admin,admin_mail,data,annotation_login,id_chat,id_ann,semestre,id_eclass) values (NULL,'$status','$vis','$new_swiki','$sess_val','$admin','$admail','$data','$ann_log','$session_id','$folder_id','$sem','$eclass')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query($query,$dbh);

		if (mysql_affected_rows($dbh) > 0) {
			$id = mysql_insert_id($dbh);
		}
		
		if ( $status == "1" ) {
			$query_upd = "update swiki set username='$usuario',password=md5('$passwd') where (id='$id')" or die ("Falha ao inserir no Banco de Dados");
			$sql = mysql_query($query_upd,$dbh);
		}
		
		// Cria diretorio para upload
		if ( !is_dir( $PATH_UPLOAD . "/" . $id ) ) {
			$oldumask = umask( 0 );
			mkdir( $PATH_UPLOAD . "/" . $id, $DEFAULT_DIR_PERMISSION );
			umask( $oldumask );
		}
		header("Location:addswiki.php");
	} else if ( $parametro == "2" ) {
		$query_chat_ann = "select id_chat, id_ann from swiki where (id='$remove')";
		$sql_chat_ann = mysql_query("$query_chat_ann");
		$tupla_chat_ann = mysql_fetch_array($sql_chat_ann);
		$query = "delete from swiki where (id='$remove')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query($query,$dbh);
			
		
		// Pegar id_sw e achar id_pag para deletar paginas
		
		// Obtem sessao de chat - invoca a API do ChatServer 2.0
		// estabelece a conexao com o banco de dados do ChatServer 2.0
		$dbh_chat = cs_db_pconnect();
 		// chama a funcao de delecao de sessoes de chat
		// voce precisa informar a id da sessao a ser deletada - $session_id
		cs_session_delete($tupla_chat_ann["id_chat"], $dbh_chat);
		//-fim [sessao de chat]
		
		//-obtem id da pasta de anotacao
		$delete_id = delete_folder($tupla_chat_ann["id_ann"], 14, 9);
		//-fim [pasta de anotacao]

		$erro = 3;
		header("Location:delswiki.php");
		exit; 
	} else if ( $parametro == "3" ) {
		$query = "SELECT id,id_ann,titulo FROM swiki where id='$atualiza'";
		$sql = mysql_query( $query, $dbh );

		// Swiki not found
		if ( mysql_num_rows( $sql ) == 0 ) {
			$erro = 2;
			header( "Location:atualiza_swiki.php");
		}

		// Update swiki
		if ($passwd == "") {
			$query = "update swiki set titulo='$new_swiki',admin='$admin',admin_mail='$admail',username='$usuario',password=NULL,status='$status',visivel='$vis',semestre='$sem',id_eclass='$eclass',annotation_login='$ann_log' where (id='$atualiza')" or die ("Falha ao inserir no Banco de Dados");
		} else {
			$query = "update swiki set titulo='$new_swiki',admin='$admin',admin_mail='$admail',username='$usuario',password=md5('$passwd'),status='$status',visivel='$vis',semestre='$sem',id_eclass='$eclass',annotation_login='$ann_log' where (id='$atualiza')" or die ("Falha ao inserir no Banco de Dados");
		}

		$tuple = mysql_fetch_array( $sql );

		// Update annotation
        	$folder_name = set_folder_name( 14, 9, $tuple["id_ann"], $tuple["titulo"] );

		header("Location:setswiki.php");
	}
}

?>
