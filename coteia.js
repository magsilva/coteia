function check(id) {
    window.opener.document.location.replace('mostra.php?ident='+id);
    window.close();
}

function check(id,index) {
    window.opener.document.location.replace('create.php?ident='+id+'&index='+index);
    window.close();
}

function Imprime() {
	window.print();
}

function validar( form ) {
	// Verifica se o campo titulo foi preenchido
	if (form.titulo.value == "") {
		alert('O campo título é de preenchimento obrigatório!');
		form.titulo.value = "";
		form.create.titulo.focus();
		return false;
	}
	if (form.passwd.lock == true ) {
		if (form.value == "") {
			alert('O campo de password é de preenchimento obrigatório!');
			document.passwd.focus();
			return false;
		}
	}
	if (form.passwd.value != form.repasswd.value) {
		alert('As senhas digitadas não coincidem!');
		form.passwd.focus();
		return false;
	}
	return true;
}

function abre(name_file,swiki) {
	window.open('$URL_COWEB/checkout.php?arq='+name_file+'&amp;swiki='+swiki,'janelachk','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=500,height=430');
}

function AbreChat(chat_folder) {
	window.open('$URL_COWEB/chat.php?id='+chat_folder,'janelachat','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=360,height=250');
}

function AbreAnotacao(id,sw_id,ann_folder) {
	window.open('$URL_COWEB/anotacao.php?p=0&amp;sw_id='+sw_id+'&amp;annotates=$URL_COWEB/mostra.php?ident='+id+'&amp;id_usuario='+id_usuario+'&amp;id_grupo='+id_grupo+'&amp;id_pasta='+ann_folder+'&amp;mostra=false','janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=480');
}

function frequencia(id_eclass) {
	window.open('$URL_COWEB/freq/index.php?curso_id='+id_eclass,'janelafreq','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=650,height=600');
}

function agenda(id) {
	window.open('$URL_NORISK/coweb_disciplina.php?user='+id,'jagenda','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=780,height=500');
}

function AbreMapa(id) {
	window.open('map.php?id='+id,'janelamap','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=520,height=480');
}
