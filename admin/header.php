<?
include_once("function.inc");
?>
<title>Administrador - CoTeia</title>
<style>
    body
    {
        color: #000000;
        background: #ffffff;
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        padding:5px;
        margin:0px;
    }

    th
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        text-align: left;
        background-color: #F0F0F0;
        border-collapse: collapse;
        border-bottom-width : 1px;
        border-top-width : 0px;
        border-left-width : 0px;
        border-right-width : 0px;
        border-style : solid;
        border-color : Gray; 
    }
     
    td  
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        text-align: center;
    }

    td.left-nobold  
    {
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        text-align: left;
    }

    a
    {
        font-weight:bold;
        color:Blue;
        outline:none;
    }
        
    p
    {
        padding: 0px 0px 0px 0px;
        margin: 0px 0px 10px 0px;
    }

    img
    {
        border:none;
    }
      
    input, select
    {
        font-size : 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
    }
        
    input.login
    {
        border-width : 1px;
        border-style : solid;
        border-color : Gray; 
        font-family : "Lucida Sans","Lucida Grande",Arial;
        font-size : 11px;
    }   

     table.box-table
    {
        border-width : 1px;  
        border-style : solid;
        border-color : Gray;
        background-color: White;
        border-collapse: collapse;
    }

    td.table-header
    {   
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        background-color: Navy;
        color: White;
        text-align: center;
    }

    td.table-footer
    {   
        font-size: 13px;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-weight: bold;
        background-color: Yellow;
        color: White;
        text-align: center;
    }
        
</style>
</head>
<body>
<center><table width="760" border="0" cellspacing="0" cellpadding="2">
    <tr><td>
    <img src="<?echo $URL_IMG?>/viewbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/editbw.gif" border="0"/>   
    <img src="<?echo $URL_IMG?>/historybw.gif" border="0"/>  
    <img src="<?echo $URL_IMG?>/indicebw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/mapbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/changesbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/uploadbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/searchbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/helpbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/chatbw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/notebw.gif" border="0"/>
    <img src="<?echo $URL_IMG?>/printbw.gif" border="0"/></td></tr>
    </table></center><br>
