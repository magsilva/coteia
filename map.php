<html>

<head>
	<title>CoTeia: Mapa do Site</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
	<style>
  	a { text-decoration: none; }
	</style>
</head>

<body>

<h1>Mapa do site</h1>

<?php
include_once("function.inc");

$dbh = coteia_connect();

$maxlevel=0;
$cnt=0;
$sql = mysql_query("SELECT ident,indexador FROM paginas WHERE ident='$id' or ident like '$id.%'",$dbh);
while ($tupla = mysql_fetch_array($sql)) {
	$level = substr_count( $tupla["ident" ], "." );
	$level = $level + 1;
	$tree[$cnt][0]= $level;
	$tree[$cnt][1]= $tupla[ "indexador" ];
	$tree[$cnt][2]= "show.php?ident=" . $tupla[ "ident" ];
	$tree[$cnt][3]=0;
	if ($tree[$cnt][0] > $maxlevel) {
		$maxlevel=$tree[$cnt][0];
	}
	$cnt++;
}

/**
* Creates the tree map (taken from file "arvore_map.inc).
*/
if(isset($PATH_INFO)) {
	$script = $PATH_INFO; 
} else {
	$script	= $SCRIPT_NAME;
}

$img_expand   = "$URL_IMG/tree_expand.png";
$img_collapse = "$URL_IMG/tree_collapse.png";
$img_line     = "$URL_IMG/tree_vertline.png";  
$img_split	= "$URL_IMG/tree_split.png";
$img_end      = "$URL_IMG/tree_end.png";
$img_leaf     = "$URL_IMG/tree_leaf.png";
$img_spc      = "$URL_IMG/tree_space.png";

for ($i=0; $i<count($tree); $i++) {
	$expand[$i]=0;
	$visible[$i]=0;
	$levels[$i]=0;
}

/*
* Obtem número de nós.
*/
  if ($p!="") {
		$explevels = explode( "|", $p );
	}
  
  $i = 0;
  while ( $i<count( $explevels ) ) {
		$expand[ $explevels[ $i ] ] = 1;
		$i++;
	}
  
  /*
  /* Encontra ultimo noh da sub-arvore.
  */
  
  $lastlevel=$maxlevel;
  for ($i=count($tree)-1; $i>=0; $i--)
  {
     if ( $tree[$i][0] < $lastlevel )
     {
       for ($j=$tree[$i][0]+1; $j <= $maxlevel; $j++)
       {
          $levels[$j]=0;
       }
     }
     if ( $levels[$tree[$i][0]]==0 )
     {
       $levels[$tree[$i][0]]=1;
       $tree[$i][3]=1;
     }
     else
       $tree[$i][3]=0;
     $lastlevel=$tree[$i][0];  
  }
  
  
  /*********************************************/
  /*  Determina os nohs visiveis               */
  /*********************************************/
  
// all root nodes are always visible
  for ($i=0; $i < count($tree); $i++) if ($tree[$i][0]==1) $visible[$i]=1;


  for ($i=0; $i < count($explevels); $i++)
  {
    $n=$explevels[$i];
    if ( ($visible[$n]==1) && ($expand[$n]==1) )
    {
       $j=$n+1;
       while ( $tree[$j][0] > $tree[$n][0] )
       {
         if ($tree[$j][0]==$tree[$n][0]+1) $visible[$j]=1;     
         $j++;
       }
    }
  }
  
  
  /*********************************************/
  /*  Formaliza a saida da arvore              */
  /*********************************************/
  
  for ($i=0; $i<$maxlevel; $i++) $levels[$i]=1;

  $maxlevel++;
  
  echo "<table cellspacing=0 cellpadding=0 border=0 cols=".($maxlevel+3)." width=100%>\n";
  echo "<tr>";
  for ($i=0; $i<$maxlevel; $i++) echo "<td width=16></td>";
  echo "<td width=100%>&nbsp;</td></tr>\n";
  $cnt=0;
  while ($cnt<count($tree))
  {
    if ($visible[$cnt])
    {
      echo "<tr>";
      
      /****************************************/
      /* linhas verticais                     */
      /****************************************/
      $i=0;
      while ($i<$tree[$cnt][0]-1) 
      {
        if ($levels[$i]==1)
            echo "<td><a name='$cnt'></a><img src=\"".$img_line."\"></td>";
        else
            echo "<td><a name='$cnt'></a><img src=\"".$img_spc."\"></td>";
        $i++;
      }
      
      /****************************************/
      /* quebra de arvore                     */
      /****************************************/         
      if ($tree[$cnt][3]==1) 
      {
        echo "<td><img src=\"".$img_end."\"></td>";
        $levels[$tree[$cnt][0]-1]=0;
      }
      else
      {
        echo "<td><img src=\"".$img_split."\"></td>";                  
        $levels[$tree[$cnt][0]-1]=1;    
      } 
      
      /********************************************/
      /* determina se eh nohs ou folha da arvore  */
      /********************************************/
      if ($tree[$cnt+1][0]>$tree[$cnt][0])
      {
        
        /****************************************/
        /* Cria parametros de expande/recolhe   */
        /****************************************/
        $i=0; $params="?id=".$id."&p=";
        while($i<count($expand))
        {
          if ( ($expand[$i]==1) && ($cnt!=$i) || ($expand[$i]==0 && $cnt==$i))
          {
            $params=$params.$i;
            $params=$params."|";
          }
          $i++;
        }
               
        if ($expand[$cnt]==0)
            echo "<td><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_expand."\" border=no></a></td>";
        else
            echo "<td><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_collapse."\" border=no></a></td>";         
      }
      else
      {
        /*************************/
        /* Folha da arvore       */
        /*************************/

        echo "<td><img src=\"".$img_leaf."\"></td>";         
      }
      
      /****************************************/
      /* saida textual                        */
      /****************************************/
      if ($tree[$cnt][2]=="")
          echo "<td colspan=".($maxlevel-$tree[$cnt][0]).">".$tree[$cnt][1]."</td>";
      else
          echo "<td colspan=".($maxlevel-$tree[$cnt][0])."><a href=\"javascript:window.opener.document.location.replace('".$tree[$cnt][2]."')\">".$tree[$cnt][1]."</a></td>";
          
      echo "</tr>\n";      
    }
    $cnt++;    
  }
  echo "</table>\n";
?>


<br />

<div align="center">
<form>
	<input type=button onClick="window.close();" value="Fechar" />
</form>
</div>

</body>

</html>
