<?
 /**
 / Script: arvore_anotacoes.php
 / Descricao: Desenha a arvore de anotacoes
 / Criador: Claudia Akemi Izeki (baseado em um script obtido na Internet) 
 **/

function init($p, $annotates, $id_pasta, $id_usuario, $id_grupo, $sw_id)
{
  global $data;
  $img_expand   = "imagem/tree_expand.gif";
  $img_collapse = "imagem/tree_collapse.gif";
  $img_line     = "imagem/tree_vertline.gif";
  $img_split    = "imagem/tree_split.gif";
  $img_end      = "imagem/tree_end.gif";
  $img_leaf     = "imagem/tree_leaf.gif";
  $img_spc      = "imagem/tree_space.gif";

  $maxlevel =0;
  $cnt      = 0;

  // Procura as pastas do nível 0 do usuário em um dado grupo
  $dataMain = get_annotations_level_zero_annotates($annotates);

  $tree[0][0] = 1;
  $tree[0][1] ='Main'; // nome da pasta
  $tree[0][2] = '';    // cfgid
  $tree[0][3] = '';
  $tree[0][4] = 0;

  if (!empty($dataMain)){
      $cnt=1;
    while ($cnt < count($dataMain)){
      $intchild = $dataMain[$cnt]["id_father"];
      $d = count($data)+1;
      $data[$d]["id_main"] = $dataMain[$cnt]["id_main"];
      $data[$d]["level"] = $dataMain[$cnt]["level"]+1;
      $data[$d]["id_father"] = $dataMain[$cnt]["id_father"];
      $data[$d]["id"] = $dataMain[$cnt]["id"];
      getchild($dataMain[$cnt]["id"], "");
      $cnt++;
    }

    $cnt=1;
     while ($cnt <= count($data)) {
       $tree[$cnt][0] = $data[$cnt]["level"]+1;
       $id = $data[$cnt]["id"];
       $attr_annotation = get_annotation_attributes($id);

       $agregado_attr = '<b>'.$attr_annotation["title"].'</b>&nbsp;&nbsp;<i>'.$attr_annotation["creation_date"];
       $tree[$cnt][1] = $agregado_attr;
       $tree[$cnt][2] = $id;
       if ($data[$cnt]["level"] == 0){
               $tree[$cnt][3] = 'main';
       } else {
               $tree[$cnt][3] = $data[$cnt]["level"];
       }

       $tree[$cnt][4]=0;
       $tree[$cnt][5]=$data[$cnt]["id_main"];
       $tree[$cnt][6]=$data[$cnt]["id_father"];
       $tree[$cnt][7]=$attr_annotation["id_owner"];
       $tree[$cnt][8]=$attr_annotation["id_group"];

       if ($tree[$cnt][0] > $maxlevel) $maxlevel=$tree[$cnt][0];

       $cnt++;
     }

     for ($i=0; $i<count($tree); $i++) {
       $expand[$i]=0;
       $visible[$i]=0;
       $levels[$i]=0;
      }

      /*********************************************/
      /*  Get Node numbers to expand               */
      /*********************************************/

      if ($p!="") $explevels = explode("|",$p);
      $i=0;
      while($i<count($explevels))
      {
        $expand[$explevels[$i]]=1;
        $i++;
      }

      /*********************************************/
      /*  Find last nodes of subtrees              */
      /*********************************************/
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
          $tree[$i][4]=1;
        }
        else
          $tree[$i][4]=0;

        $lastlevel=$tree[$i][0];
      }
      /*********************************************/
      /*  Determine visible nodes                  */
      /*********************************************/

      $visible[0]=1;   // root is always visible
      for ($i=0; $i<count($explevels); $i++)
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
      /*  Output nicely formatted tree             */
      /*********************************************/

      for ($i=0; $i<$maxlevel; $i++) $levels[$i]=1;

      $maxlevel++;

      echo "<table STYLE='font-family:tahoma;font-size:13px;' width='100%' 
cellspacing=0 cellpadding=0 border=0 cols=".($maxlevel+3).">\n";
      echo "<tr>";
      for ($i=0; $i<$maxlevel; $i++) echo "<td width=16></td>";
      echo "<td width=\"100%\"></td></tr>\n";
      $cnt=1;
      while ($cnt<count($tree))
      {
        if ($visible[$cnt])
        {
          /****************************************/
          /* start new row                        */
          /****************************************/
          echo "<tr>";

          /****************************************/
          /* vertical lines from higher levels    */
          /****************************************/
          $i=1;
          while ($i<$tree[$cnt][0]-1)
          {
            if ($levels[$i]==1){
                echo "<td><img src=\"".$img_line."\"></td>";
            }
          else
                echo "<td><img src=\"".$img_spc."\"></td>";
            $i++;
          }

          /****************************************/
          /* corner at end of subtree or t-split  */
          /****************************************/
          if ($tree[$cnt][4]==1)
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
          /* Node (with subtree) or Leaf (no subtree) */
          /********************************************/
          if ($tree[$cnt+1][0]>$tree[$cnt][0])
          {

            /****************************************/
            /* Create expand/collapse parameters    */
            /****************************************/
            $i=0; $params="?p=";
            while($i<count($expand))
            {
              if ( ($expand[$i]==1) && ($cnt!=$i) || ($expand[$i]==0 && $cnt==$i))
              {
                $params=$params.$i;
                $params=$params."|";
              }
              $i++;
            }

            $id_anot = $tree[$cnt][2];
            if ($expand[$cnt]==0)
                echo "<td><a href=\"anotacao.php".$params."&mostra=false&id_pasta=$id_pasta&sw_id=$sw_id&id_usuario=$id_usuario&id_grupo=$id_grupo&annotates=$annotates&id_anotacao=$id_anot\"><img src=\"".$img_expand."\" border=no></a></td>";
            else{
                if ($tree[$cnt][1] != "Main")
                echo "<td><a href=\"anotacao.php".$params."&mostra=false&id_pasta=$id_pasta&sw_id=$sw_id&id_usuario=$id_usuario&id_grupo=$id_grupo&annotates=$annotates&id_anotacao=$id_anot\"><img src=\"".$img_collapse."\" border=no></a></td>";
            }
          }
          else
          {
            /*************************/
            /* Tree Leaf             */
            /*************************/

            echo '<td><img src="'.$img_leaf.'"></td>';
          }

          /****************************************/
          /* output item text                     */
          /****************************************/
          $nome = get_user_name($tree[$cnt][7], "");
          $grupo = get_group_name($tree[$cnt][8], "");

          echo '<td  colspan='.($maxlevel-$tree[$cnt][0]+1).'>';
          echo '&nbsp;<a href="anotacao.php?p='.$p.'&annotates='.$annotates.'&id_pasta='.$id_pasta.'&id_usuario='.$id_usuario.'&id_grupo='.$id_grupo.'&sw_id='.$sw_id.'&mostra=true'.'&id_anotacao='.$tree[$cnt][2].'">'.$tree[$cnt][1].' by '.$nome.' ('.$grupo.')</a></i>';
          echo "</td>";

             /****************************************/
             /* end row                              */
             /****************************************/

             echo "</tr>\n";
           }
           $cnt++;
         }
         echo "</table>\n";
  }
} // fim function init

function getchild ($intchild, $level)
{
  global $data;

  include_once("function.inc");

  $dataSub = get_annotation_children($intchild);

  if (!empty($dataSub)){
    $cnt=1;
    while ($cnt < count($dataSub)){
      $d = count($data)+1;
      $data[$d]["id_main"]   = $dataSub[$cnt]["id_main"];
      $data[$d]["level"]     = $dataSub[$cnt]["level"]+1;
      $data[$d]["id_father"] = $dataSub[$cnt]["id_father"];
      $data[$d]["id"]        = $dataSub[$cnt]["id"];

      getchild($dataSub[$cnt]["id"], "");
      $cnt++;
    }
  }
  return($dataSub);
}



