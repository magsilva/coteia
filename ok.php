<html>
<head>
<script LANGUAGE ="JavaScript">
function check(id) {
    window.opener.document.location.replace('mostra.php?ident='+id);
    window.close();
}
</script>
</head>
<body onLoad="check(<?echo $id?>)">
</body>
</html>
