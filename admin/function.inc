<?php

include_once( "../config.php.inc" );

/*
 * NOME:		cs_db_pconnect
 * DESCRICAO:		Realiza a conexao do chat com o DBMS MySQL 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function cs_db_pconnect(){
        global $cs_dbhost, $cs_dbuser, $cs_dbpword;

        return(mysql_pconnect($cs_dbhost, $cs_dbuser, $cs_dbpword));
}

/*
 * NOME:                cs_session_create
 * DESCRICAO:           Cria uma nova sessao de chat
 * PAR. ENTRADA:        $session_name - nome da sessao a ser criada
 *                      $moderator - username do moderador da sessao
 *                      $dbh - handler da base de dados
 * PAR. SAIDA:          --
 * RETORNO:             a id da sessao recem-criada, ou zero em caso de insucesso
 * OBSERVACOES:         O moderador da sessao tem poderes especiais, podendo expulsar
 *                      usuarios inconvenientes
 */
function cs_session_create($session_name, $moderator, $dbh){
	global $cs_dbname;

	$select_db = mysql_select_db($cs_dbname, $dbh);
	$query = "INSERT INTO session VALUES (null, " . quotes($session_name) . " , now(), " . quotes($moderator) . ", now())";
	$query_res = mysql_query($query,$dbh);

	return mysql_insert_id($dbh); 
}
  
/*
 * NOME:                cs_session_delete
 * DESCRICAO:           Deleta uma sessao de chat existente
 * PAR. ENTRADA:        $session_id - id da sessao a ser deletada
 *                      $dbh - handler da base de dados
 * PAR. SAIDA:          --
 * RETORNO:             Retorna TRUE se a operacao for bem sucedida e FALSE caso contrario
 * OBSERVACOES:         --
 */
function cs_session_delete($session_id, $dbh) {
	global $cs_dbname, $cs_msg_tbl, $cs_access_tbl;
 
	$select_db = mysql_select_db($cs_dbname,$dbh);
	$query = "DELETE FROM session WHERE session_id = $session_id";
	$query_res = mysql_query($query, $dbh);
	if ($query_res) {
		$query = "DELETE FROM $cs_msg_tbl WHERE session_id = $session_id";
		$query_res = mysql_query($query, $dbh);
		if ($query_res) {
			$query = "DELETE FROM $cs_access_tbl WHERE session_id = $session_id";
			$query_res = mysql_query($query, $dbh);
		}
	}

	if ($query_res){
		return TRUE;
	} else {
		return FALSE;
	}
}
 
/*
 * funcao auxiliar - quotes
 * Retorna o parametro de entrada entre aspas duplas, ou retorna NULL caso o
 * parametro seja uma string nula.
 */
function quotes($var){
        if ($var == ""){
                return "NULL";
        } else {
                return '"'.$var.'"';
        }
}  

/*
 * NOME:		anota_db_pconnect
 * DESCRICAO:		Realiza a conexao da anotacao com o DBMS MySQL 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function anota_db_pconnect() {

        global $anota_dbhost, $anota_dbuser, $anota_dbpword;

        return(mysql_pconnect($anota_dbhost, $anota_dbuser, $anota_dbpword));

}

/*
 * NOME:		user_db_pconnect
 * DESCRICAO:		Realiza a conexao de usuarios com o DBMS MySQL 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function user_db_pconnect() {

        global $user_dbhost, $user_dbuser, $user_dbpword;

        return(mysql_pconnect($user_dbhost, $user_dbuser, $user_dbpword));

}

/*
 * NOME:		user_id_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function user_id_exists($id,$dbh)  {
    
    global $user_dbname;

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT * FROM t_user WHERE id=$id";
    $resultado = mysql_query($comando_sql);
   
    if (mysql_num_rows($resultado))
      return 1;
    
    return 0;
    
}

/*
 * NOME:		group_id_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function group_id_exists($id,$dbh)  {
    
    global $user_dbname;

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT * FROM t_group WHERE id=$id";
    $resultado = mysql_query($comando_sql);
   
    if (mysql_num_rows($resultado))
      return 1;
    
    return 0;
    
}

/*
 * NOME:		get_folder_attributes
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_attributes($id_folder,$dbh)  {
    // Verifica entrada
    $id_folder = (int) $id_folder;
    if (!$id_folder)
       return -1;
    
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT name, id_main, id_father, level, id_owner, id_group, creation_date,id_last_user, id_last_group, last_modified, access_permission FROM folder WHERE id=$id_folder";
    $resultado = mysql_query($comando_sql,$dbh);
	
    if (!mysql_affected_rows($dbh))
       return 0;
  
    $properties["name"] = mysql_result($resultado, 0, "name");
    $properties["id_main"] = mysql_result($resultado, 0, "id_main");
    $properties["id_father"] = mysql_result($resultado, 0, "id_father");
    $properties["level"] = mysql_result($resultado, 0, "level");
    $properties["id_owner"] = mysql_result($resultado, 0, "id_owner");
    $properties["id_group"] = mysql_result($resultado, 0, "id_group");
    $properties["creation_date"] = mysql_result($resultado, 0, "creation_date");
    $properties["id_last_user"] = mysql_result($resultado, 0, "id_last_user");
    $properties["id_last_group"] = mysql_result($resultado, 0, "id_last_group");
    $properties["last_modified"] = mysql_result($resultado, 0, "last_modified");
    $properties["access_permission"] = mysql_result($resultado, 0, "access_permission");
    
    return $properties;
}

/*
 * NOME:		get_folder_father
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_father($id_folder,$dbh) {
  // Verifica a entrada
  $id_folder = (int)$id_folder;
  if (!$id_folder)
     return -1;  

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
     
  $comando_sql = "SELECT id_father FROM folder WHERE id = $id_folder";
  $resultado = mysql_query($comando_sql,$dbh);
  
  if (!mysql_affected_rows($dbh)) // erro provavel: pasta inexistente
       return -2;
     
  return mysql_result($resultado, 0, "id_father");
} 

/*
 * NOME:		get_folder_ancestral
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_ancestral($id_folder,$dbh) {
  // Verifica a entrada
  $id_folder = (int) $id_folder;
  if (!$id_folder)
     return -1;
  
  $father = get_folder_father($id_folder,$dbh);
  if ($father == -2) // erro provavel: pasta inexistente
     return 0;
     
  $array[0] = $father;  
  $i = 1;
  while ($father != 0) {   
  	$father = get_folder_father($father,$dbh);
  	if ($father == -2) // erro provavel: pasta inexistente
       		return 0;
    	$array[$i] = $father;
    	$i++;
  }
     
  return $array;
}

/*
 * NOME:		get_folder_level
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_level($id_folder,$dbh) { 
    // Verifica entrada
    $id_folder = (int)$id_folder;
    if (!$id_folder)
       return -1;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT level FROM folder WHERE id=$id_folder";
    $resultado = mysql_query($comando_sql);
    
    if (!mysql_affected_rows($dbh))
         return -2; // erro provavel: pasta inexistente
    
    return mysql_result($resultado, 0, "level");
}

/*
 * NOME:		get_folder_main
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_main ($id_folder,$dbh) { 
    // Verifica entrada
    $id_folder = (int)$id_folder;
    if (!$id_folder)
       return -1;
 
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT id_main FROM folder WHERE id=$id_folder";
    $resultado = mysql_query($comando_sql);
    
    if (!mysql_affected_rows($dbh))
         return 0; // erro provavel: pasta inexistente
    
    return mysql_result($resultado, 0, "id_main");
}

/*
 * NOME:		test_userclass_can_writes_folder_only
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function test_userclass_can_writes_folder_only ($id_folder, $user_class,$dbh)  {  

    // Verifica a entrada   
    $id_folder = (int) $id_folder;
    if (!$id_folder || !($user_class == "owner")&& !($user_class == "group")&& !($user_class == "other") && !($user_class == "guest"))
       return -1;
    
    $properties = get_folder_attributes($id_folder,$dbh);
    
    if (!$properties) // erro provavel: pasta inexistente
       return -2;
    
    switch ($user_class){
       case "owner":
           if (!($properties["access_permission"] & 64)){
             return 0;
           }
           break;   
     case "group":
           if (!($properties["access_permission"] & 16)){
             return 0;
           }
           break;
       default: // other
           if (!($properties["access_permission"] & 4)){
             return 0;
           }
    } // fim switch
    return 1;
}

/*
 * NOME:		test_user_can_writes_folder
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function test_user_can_writes_folder($id_folder, $id_user, $id_group,$dbh) {
    // Verifica a entrada
    $id_folder = (int)$id_folder;
    $id_user   = (int)$id_user;   
    $id_group  = (int)$id_group;
    if ((!$id_folder)||(!$id_user)||(!$id_group))
       return -1;
     
    // Verifica se id_user e id_group existem
    $user_dbh = user_db_pconnect();

    if (!user_id_exists($id_user,$user_dbh))
       return -2;

    if (!group_id_exists($id_group,$user_dbh))
       return -3;
  
    // Procura id_owner e id_group da pasta

    $properties = get_folder_attributes($id_folder,$dbh);
    if (!$properties) // erro provavel: pasta inexistente
       return -4;
    
    if ($properties["id_owner"] == $id_user)
         $user_class = "owner";
    else if ($properties["id_group"] == $id_group)
         $user_class = "group";
    else if ($id_user == 0) // é guest. Guest nunca pode escrever
         return 0;
    else
         $user_class = "other";
    
    // 3 - Caso a permissao de escrita daquela pasta para o usuario tenha sido positiva,
    //     pode ser que os ancestrais diretos da pasta nao tenha

    $ancestral = get_folder_ancestral($id_folder,$dbh);
    if (!$ancestral) // erro provavel: pasta inexistente
        return -4;
     
    $i = 0;  
    reset($ancestral);
    while(current($ancestral)){ // para cada ancestral verifica se a pasta possui permissao de escrita

      $aux = test_userclass_can_writes_folder_only($ancestral[$i], $user_class,$dbh);
      if ($aux == -1) // entrada invalida
         return -1;
      else if ($aux == -2) //erro provavel: pasta inexistente
         return -4;
      else if (!$aux) // usuario nao possui permissao
        return 0;
	next($ancestral);
      $i++;
    }
    return 1;
    
}   

/*
 * NOME:		folder_exist
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function folder_exists($id,$dbh) { 
    // Verifica entrada
    $id = (int)$id;
    if (!$id)
       return -1;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
      
    $comando_sql = "SELECT * FROM folder WHERE id=$id";
    $resultado = mysql_query($comando_sql,$dbh);

    if (!mysql_num_rows($resultado))
       return 0;
    else{
      mysql_free_result($resultado);
      return 1;
    }
    
} 

/*
 * NOME:		get_folder_children
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_children($id_father,$dbh)  {
    // Verifica entrada
    $id_father = (int)$id_father;
    if (!$id_father)
       return -1;
                       
    // Verifica se folder existe
   
    if (!folder_exists($id_father,$dbh))
       return 0;
      
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT id_main, level, id_father, id, name FROM folder WHERE id_father = $id_father ORDER BY name";
     
    $resultado = mysql_query($comando_sql);
    $array = array();
    $array[0] = mysql_num_rows($resultado);
    
    $i = 1;
    while ($linha = mysql_fetch_array($resultado)){
      $array[$i]["id_main"]   = mysql_result($resultado, $i-1, "id_main");
      $array[$i]["level"]     = mysql_result($resultado, $i-1, "level");
      $array[$i]["id_father"] = mysql_result($resultado, $i-1, "id_father");
      $array[$i]["id"] = mysql_result($resultado, $i-1, "id");
      $array[$i]["name"] = mysql_result($resultado, $i-1, "name");
      $i++;
    }

    mysql_free_result($resultado);
    
    return $array;
       
} 

/*
 * NOME:		transform_accesspermission
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function transform_accesspermission($array)  {
    if (!is_array($array))
        return -1;
  
    $access_permission = 0;
  
    if ($array["guest_write"])
         $access_permission += 1;
  
    if ($array["guest_read"])
        $access_permission += 2;
  
    if ($array["other_write"])
         $access_permission += 4;
    
    if ($array["other_read"])
         $access_permission += 8;

    if ($array["group_write"])
         $access_permission += 16;
     
    if ($array["group_read"])
         $access_permission += 32;
 
    if ($array["owner_write"])
         $access_permission += 64;
    
    if ($array["owner_read"])
         $access_permission += 128;
    
    return $access_permission;
    
} 

/*
 * NOME:		subfolders_exist
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function subfolders_exist($id,$dbh)  {
    // Verifica entrada
    $id = (int)$id;
    if (!$id)
       return -1;
  
 // Verifica existencia de pasta
    if (!folder_exists($id,$dbh))
       return -2;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
     
    $comando_sql = "SELECT id FROM folder WHERE id_father=$id";
    $resultado = mysql_query($comando_sql);
  
    if (!mysql_num_rows($resultado))
       return 0;
    return 1;
    
}

/*
 * NOME:		annotations_exist_in_folder
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function annotations_exist_in_folder($id,$dbh)  {
    // Verifica entrada
    $id = (int)$id;
    if (!$id)
       return -1;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
  
    // Verifica se pasta existe
    $comando_sql = "SELECT * FROM folder WHERE id=$id";
    $resultado = mysql_query($comando_sql);
    if (!mysql_affected_rows($dbh))  
       return -2; // erro provavel: anotacao inexistente
     
    $comando_sql = "SELECT * FROM rel_annotation_folder WHERE id_folder=$id";
    $resultado = mysql_query($comando_sql,$dbh);
     
    if (mysql_num_rows($resultado)){
      mysql_free_result($resultado);
      return 1;
    }
    else
      return 0;
}

/*
 * NOME:		folder_name_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function folder_name_exists ($id_user, $id_group, $id_father, $name,$dbh) {
    $id_user   = (int)$id_user;
    $id_father = (int)$id_father; 
       
    // Verifica entrada
    if (!$id_user || $name == "")   
       return -1;
  
    $user_dbh = user_db_pconnect();

    if (!user_id_exists($id_user,$user_dbh))
       return -2;
    if (!group_id_exists($id_group,$user_dbh))
       return -3;
    // Caso o id_father seja igual a zero, o usuario deseja inserir uma pasta
    // no nivel zero. Logo, verificar se ja nao existe uma pasta no nivel zero
    // com o mesmo nome
    if (!$id_father){
       $id_group = (int)$id_group;
       if (!$id_group)
          return -1;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
    
       $comando_sql = "SELECT name FROM folder WHERE id_owner=$id_user AND id_group=$id_group AND name='$name' AND level=0";
       $resultado = mysql_query($comando_sql,$dbh);
       
       if (mysql_num_rows($resultado))
          return 1;
       else
          return 0;
    }
    $ids_pastas = get_folder_children($id_father,$dbh);
    
    if (!$ids_pastas)
       return -4; // pasta pai nao existe
    
    if ($ids_pastas[0] > 0)
    {
      $i = 1;
      while ($i <= $ids_pastas[0]){
         if ($ids_pastas[$i]["name"] == $name)
            return 1;
         $i++;
      } // fim while
    }
    return 0;
}

/*
 * NOME:                create_folder
 * DESCRICAO:           cria uma pasta de anotacao
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function create_folder($name, $id_father, $id_owner, $id_group, $access_permission) {
          
    global $anota_dbname;

    $id_father = (int)$id_father;
    $flag      = (int)$flag;
       
    // Verifica entrada
    $id_owner = (int)$id_owner;
    $id_group = (int)$id_group;
    if (!$id_owner || !$id_group || $name == "")
       return -1;   
       
    $dbh = anota_db_pconnect();

    # seleciona base de dados
    mysql_select_db($anota_dbname,$dbh);
    
    if ($id_father) { // se possui pai tem que testar se pode criar subpasta...
    // Verifica se o usuario pode criar a pasta

    $aux = test_user_can_writes_folder($id_father, $id_owner, $id_group,$dbh);
    if ($aux == -2) // id user inexistente
	return -2;
    else if ($aux == -3) // id group inexistente
	return -3;
    else if ($aux == -4) // erro provavel: pasta pai inexistente
	return -4;
    else if (!$aux) // usuario nao pode criar anotacao
	return 0;
    
    //Procura o nível da pasta pai

    $level = get_folder_level($id_father,$dbh);
    if ($level == -2) // erro provavel: pasta pai inexistente
	return -4;
    $level = $level + 1;
    
    //Procura o main da pasta pai

    $id_main = get_folder_main($id_father,$dbh);
    }
    else $level = 0;
          
    // Verifica se ja existe uma pasta com o mesmo nome no mesmo nivel e filho do mesmo pai

    $aux = folder_name_exists($id_owner, $id_group, $id_father, $name, $dbh);
    if ($aux == -2)
       return -2;  // usuario inexistente
    else if ($aux == -3) // grupo inexistente
       return -3;
    else if ($aux == -4)
       return -4; // erro provavel: pasta pai nao existe
    if ($aux == 1) // ja existe outra pasta com mesmo nome
       return -5;
       
    // Verifica se permissao de acesso eh vetor ou inteiro
    if (is_array($access_permission)) 

    $access_permission = transform_accesspermission($access_permission);

    $id_main = (int)$id_main;
    
    // Cria a pasta
    $comando_sql = "INSERT INTO folder VALUES (NULL, '$name', $id_main, $id_father, $level, $id_owner, $id_group,NOW(), $id_owner, $id_group, NOW(), $access_permission)";
    mysql_query($comando_sql,$dbh);

    if (!mysql_affected_rows($dbh))
        return -6;
      
    $id = mysql_insert_id($dbh);
     
    if (!$id_main) { // No caso de ser a primeira pasta a ser criada no nivel 0.
	$comando_sql = "UPDATE folder SET id_main = $id WHERE id=$id";
        mysql_query($comando_sql);
    if (!mysql_affected_rows($dbh)) { // erro na atualizacao
	// Apaga a pasta inserida
	$comando_sql = "DELETE FROM folder WHERE id=$id";
        mysql_query($comando_sql);
        return -6;
       }
    }
        
    return $id;
} 

/*
 * NOME:                delete_folder
 * DESCRICAO:           apaga uma pasta de anotacao
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function delete_folder($id_folder, $id_owner, $id_group) {

    global $anota_dbname;

    $dbh = anota_db_pconnect();

    # seleciona base de dados
    mysql_select_db($anota_dbname,$dbh);

    // Verifica entrada
    $id_folder = (int)$id_folder;
    $id_owner  = (int)$id_owner;
    $id_group  = (int)$id_group;
    $flag      = (int)$flag;
    if (!$id_folder || !$id_owner || !$id_group || (($flag != 0)&&($flag != 1)))
       return -1;    // entrada invalida
       
    
    // Verifica se o usuario tem permissao de escrita para a pasta
    $aux = test_user_can_writes_folder($id_folder, $id_owner, $id_group,$dbh);
    if ($aux == -2) // usuario inexistente
       return -2;
    else if ($aux == -3) // grupo inexistente
       return -3;
    else if ($aux == -4) // erro provavel: pasta inexistente
       return -4;
    else if (!$aux) // usuario nao pode apagar anotacao
       return 0;
   
    // Verifica se existem subpastas
    if (subfolders_exist($id_folder,$dbh) == 1)
       return -5;
    
    // Verifica se existem anotacoes
    if (annotations_exist_in_folder($id_folder,$dbh) == 1)
       return -6;
    
    $comando_sql = "DELETE FROM folder WHERE id=$id_folder";
    $resultado = mysql_query($comando_sql);
    
    if (mysql_affected_rows($dbh)) 
	return 1; 

    return -7; // erro na delecao
       
} 

/*
 * NOME:                set_folder_name
 * DESCRICAO:           modifica o nome de uma pasta de anotacao
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function set_folder_name ($id_user, $id_group, $id_folder, $name)  {

    global $anota_dbname;

    $dbh = anota_db_pconnect();

    # seleciona base de dados
    mysql_select_db($anota_dbname,$dbh);

    // Verificar entrada
    $id_user   = (int)$id_user;
    $id_group  = (int)$id_group;
    $id_folder = (int)$id_folder;
    if (!$id_user || !$id_group || !$id_folder || ($name == ""))
       return -1;

    $aux = test_user_can_writes_folder ($id_folder, $id_user, $id_group,$dbh);

    if ($aux == -2) // usuario inexistente   
       return -2;
    else if ($aux == -3) // grupo inexistente
       return -3;
    else if ($aux == -4) // erro provavel: pasta inexistente
       return -4;
    else if (!$aux) 
       return 0;  // usuario nao pode alterar nome de pasta
  
    // Procura o pai da pasta
    $id_father = get_folder_father($id_folder,$dbh);
    
    // Verifica se algum irmao ja nao possui o mesmo nome

    if (folder_name_exists($id_user, $id_group, $id_father, $name,$dbh))
       return -5; // pasta ja existente
    
    $comando_sql = "UPDATE folder SET name='$name' WHERE id=$id_folder";
    
    $resultado = mysql_query($comando_sql);
    
    if ($resultado)
	  return 1;

    return 0;
       
} 

/*
 * NOME:                ce_db_pconnect
 * DESCRICAO:           Realiza a conexao de cursos do eClass com o DBMS MySQL
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             Retorna o handler da base de dados
 * OBSERVACOES:
 */
function ce_db_pconnect() {

        global $ce_dbhost, $ce_dbuser, $ce_dbpword;

        return(mysql_pconnect($ce_dbhost, $ce_dbuser, $ce_dbpword));

}

/*
 * NOME:		db_connect
 * DESCRICAO:		Realiza a conexao com o DBMS MySQL
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function db_connect(){

	global $dbhost, $dbuser, $dbpass;

	return(mysql_connect($dbhost, $dbuser, $dbpass));

}

/*
 * NOME:                autentica
 * DESCRICAO:           Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:        $usuario - usuario & $passwd - senha & $dbh - handler do banco
 * PAR. SAIDA:          --
 * RETORNO:             TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:         --
 */
function autentica($usuario,$senha,$dbh){

      global $dbname;

      # seleciona base de dados
      mysql_select_db($dbname,$dbh);

      $query = mysql_query("select login,email from admin where login='$usuario' AND pass=md5('$senha')");

      # verifica se o usuário é valido
      if (mysql_num_rows($query) > 0) {

            return true;

     }
     else return false;

}

class coweb_session {

var $key;
var $timeout;

/*
 * NOME:                start
 * DESCRICAO:           Inicializa a sessao gerando uma chave de sessao
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             Chave de sessao ou ID
 * OBSERVACOES:         
 */
function start($timeout = "") {

//cria um array com todas as letras do alfabeto
$letters = range("a","z");
$key = "";

//gera a chave de sessao no formato #a#aa#
for($i = 0; $i < 6; $i++) {
if(($i == 0) || ($i == 2) || ($i == 5))
$key .= rand(0,9);
if(($i == 1) || ($i == 3) || ($i == 4))
$key .= $letters[rand(0,25)];
}

//armazena a chave de sessao em um metodo da classe session
$this->key = $key;

//verifica se o usuario definiu um timeout e armazena esse valor
if($timeout == "")
$this->timeout = 300; // five minutes
else
$this->timeout = $timeout;

return 0;
} 

/*
 * NOME:                register
 * DESCRICAO:           Registra o valor da sessao
 * PAR. ENTRADA:        Valor da sessao
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function register($val) {

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

//caso a chave nao esteja gerada, a fcao start() eh chamada
if($this->key == "")
$this->start();

$query = "INSERT INTO sessions (sess_key, val, ip, sec_expire, access) VALUES ('" . $this->key. "', '" . addslashes($val) . "', '" . $_SERVER["REMOTE_ADDR"] . "' , " . $this->timeout . "," . time() .")";
$insert = mysql_query($query,$dbh);

//seta o cookie que armazenarah a chave de sessao
setcookie('sess_key',$this->key);
} 

/*
 * NOME:                read
 * DESCRICAO:           Realiza a leitura do valor de sessao e atualiza o BD
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         Verifica se a sessao expirou 
 */
function read() {

global $sess_val;

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

//verifica se o cookie existe
if(!$_COOKIE["sess_key"]) {
header("Location: index.php");
exit;
}

//busca a chave de sessao do cookie
$this->key = $_COOKIE["sess_key"];

//busca o valor da sessao
$query = mysql_query("SELECT val FROM sessions WHERE sess_key = '" . $this->key . "'") or die("query failed - line 55");

if(mysql_num_rows($query) == 0) {
header("Location: index.php");
exit;
}

$fetch = mysql_fetch_array($query);

//armazena o valor da sessao 
$sess_val = stripslashes($fetch["val"]);

//verifica se a sessao expirou
$this->expire();

//caso a sessao nao tenha expirado, atualiza o valor de ultimo acesso
$update = mysql_query("UPDATE sessions SET access = " . time() . " WHERE sess_key = '" . $this->key . "'") or die("query failed - line 70");

} 

/*
 * NOME:                expire
 * DESCRICAO:           Testa se o usuario ficou inativo pelo timeout definido
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function expire() {

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

//busca no BD os valores de ultimo acesso e tempo de expiracao
$query = mysql_query("SELECT access, sec_expire FROM sessions WHERE sess_key = '" . $this->key . "'") or die("query failed - line 78");
$fetch = mysql_fetch_array($query);

$access = $fetch["access"];
$expire = $fetch["sec_expire"];

//verifica se a sessao expirou - baseado no timeout definido
if((time() - $access) >= ($expire)) {
$this->destroy();
header("Location: index_expire.php");
}
} 

/*
 * NOME:                replace
 * DESCRICAO:           Atualiza o valor da sessao
 * PAR. ENTRADA:        Valor da sessao
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         --
 */
function replace($val) {

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

//busca a chave de sessao no cookie
$this->key = $_COOKIE["sess_key"];

//atualiza a base de dados com o novo valor
$query = mysql_query("UPDATE sessions SET val = '" . $val . "' WHERE sess_key = '" . $this->key) or die("query failed - line 77");
}

/*
 * NOME:                destroy
 * DESCRICAO:           Finaliza a sessao
 * PAR. ENTRADA:        --
 * PAR. SAIDA:          --
 * RETORNO:             --
 * OBSERVACOES:         Caso o usuario saia da sessao sem passar pelo logout, a sessao ficarah na BD. Para 
 *                      finaliza-la eh necessario rodar o script session_destroy.php
 */
function destroy($key = "") {

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

//busca a chave de sessao no cookie
$this->key = $_COOKIE["sess_key"];

//apaga a tupla de sessao na base de dados
$query = mysql_query("DELETE FROM sessions WHERE sess_key = '" . $this->key . "'") or die("query failed - line 86");

//remove o cookie do computador do usuario
$delete = setcookie('sess_key' ,$this->key, time()-3600);

} 

} 

?>
