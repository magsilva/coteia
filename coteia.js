/**
* Verifica o estado do envio de um arquivo.
*/
function StatusUpload( ok ) {
	if (ok == 1) {
		alert( "O arquivo foi transferido com sucesso!" );
	} else if (ok == 2) {
		alert( "Erro ao gravar arquivo (tamanho máximo permitido = 10 MiB)" );
	} else if (ok == 3) {
		alert( "O arquivo já existe." );
	} else if (ok == 4) {
		alert( "Extensão de arquivo inválida. São validos: .bz2, .gz, .pdf, .ps, .txt, .xml" );
	}
}

function check(id,index) {
	if ( typeof( index ) ==  "undefined" ) {
		window.opener.document.location.replace('mostra.php?ident=' + id);
	} else {
		window.opener.document.location.replace('create.php?ident='+id+'&index='+index);
	}
	window.close();
}

/**
* Imprime a página atual.
*/
function Imprime() {
	window.print();
}

/**
* Valida os dados da página wiki sendo editada/criada.
*/
function validar( form ) {
	if ( form.title.value == "" ) {
		alert('O campo título é de preenchimento obrigatório!');
		form.title.focus();
		return false;
	}
	
	if ( form.lock == true ) {
		if ( form.password.value == "") {
			alert('O campo de password é de preenchimento obrigatório!');
			form.passwd.focus();
			return false;
		}
	}

	if ( form.passwd.value != form.repasswd.value ) {
		alert('As senhas digitadas não coincidem!');
		form.repasswd.focus();
		return false;
	}

	return true;
}


function AbreChat(chat_folder) {
	window.open('chat.php?id='+chat_folder,'janelachat','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=360,height=250');
}

function AbreAnotacao(id,swiki_id,ann_folder) {
	window.open('plugins/annotation/annotation.php?p=0&swiki_id='+swiki_id+'&annotates=show.php?wikipage_id='+id+'&id_pasta='+ann_folder+'&mostra=false','janelaann','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=700,height=480');
}

function frequencia(id_eclass) {
	window.open('freq/index.php?curso_id='+id_eclass,'janelafreq','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=650,height=600');
}

function agenda(id) {
	window.open('norisk/coweb_disciplina.php?user='+id,'jagenda','toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=780,height=500');
}

function AbreMapa(id) {
	window.open('map.php?id='+id,'janelamap','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=520,height=480');
}
