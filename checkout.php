<?php 
include_once("function.inc");
?>

<html>

<head>
	<title><?php echo $arq;?></title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<br />

<h2>Arquivo para Download</h2>

<hr />
<br />

<a href="<?php echo $URL_UPLOAD . "/" . $swiki . "/" . rawurlencode( $arq );?>"><?php echo $arq;?></a>

</body>
 
</html>
