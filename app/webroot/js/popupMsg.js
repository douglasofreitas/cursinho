//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn(400);
		$("#popupMsg").fadeIn(400);
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut(400);
		$("#popupMsg").fadeOut(400);
		popupStatus = 0;
	}
}

//confirmation message
function confirmMsg(title,text,linkyes,linkno,yes,no){
  popupMsg(title, text +'<br><br><a href="'+linkyes+'">'+yes+'</a>&nbsp;&nbsp;<a href="'+linkno+'">'+no+'</a>');
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupMsg").height();
	var popupWidth = $("#popupMsg").width();
	//centering
	$("#popupMsg").css({
		"position": "fixed",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	//LOADING POPUP
	//Click the button event!
	//$("#button").click(function(){
		//centering with css
		//centerPopup();
		//load popup
		//loadPopup();
	//});
	//CLOSING POPUP
	//Click the x event!
	$("#popupMsgClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});
});

// função que chama o popupMsg
function popupMsg (titulo,corpo,tipo) {

	// para utilizar a popup em casos específicos
	if (tipo == 'programacao') {
		$(document).ready(function(){
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('/' + corpo + '/home-event/schedule-popup.php');
			$('#popupMsgCorpo').css('width','693px');
			$('#popupMsgCorpo').css('height','465px');
			$('#popupMsg').css('width','693px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});
	}
	else if (tipo == 'boleto') {
		$(document).ready(function(){
			var pagina = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').html('<iframe width="691px" height="425px" id="ifrm" src="'+pagina+'"></iframe>');
			$('#popupMsg').css('width', '693px');
			$('#popupMsg').css('height', '500px');
			centerPopup();
			loadPopup();
		});
	}
	else if (tipo == 'notificar_usuario') {
		$(document).ready(function(){
			var id_fatura = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('user-notify-pop-up.php?id_fatura='+id_fatura);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});
	} else if (tipo == 'comprov_nao_enviado'){
		$(document).ready(function(){
			var id_inscricao = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('user-notify-pop-up.php?id_inscricao='+id_inscricao);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});
	}
	else if (tipo == 'comprov_ok'){
		$(document).ready(function(){
			var id_inscricao = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('user-notify-pop-up.php?id_inscricao='+id_inscricao+'&ok=ok');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});
	}
	else if (tipo == 'notificar_usuario-m') {
		$(document).ready(function(){
			var id_fatura = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('user-notify-pop-up-pagamento-ok.php?id_fatura='+id_fatura+'&mailtag='+tipo);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}
	else if (tipo == 'notificar-parecer') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('notify-referee.php?id_artigo='+id_artigo+'&mailtag=submission-status');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}	
	else if (tipo == 'carta_aceite') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('abstract-situation.php?id_artigo='+id_artigo+'&mailtag=carta-aceite');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}

        else if (tipo == 'carta_modificacao') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('send-modification-status.php?id_artigo='+id_artigo+'&mailtag=submission-comment');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}
        
	else if (tipo == 'ciencia_submissao') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('send-responsible-approval.php?id_artigo='+id_artigo+'&mailtag=responsible-approval');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}
	
	else if (tipo == 'forcar-ciencia') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('force-responsible-approval.php?id_artigo='+id_artigo);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}
	
	
	else if (tipo == 'confirmacao-orientador') {
		$(document).ready(function(){
			var id_convite = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('send-leader-approval.php?invitation='+id_convite);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','520px');
			$('#popupMsg').css('width','420px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}

        else if (tipo == 'recusar_convite') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('reject-invitation.php?id_manuscript='+id_artigo);
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','250px');
			$('#popupMsg').css('width','420px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});
	}
	
	else if (tipo == 'carta_recusa') {
		$(document).ready(function(){
			var id_artigo = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('abstract-situation.php?id_artigo='+id_artigo+'&mailtag=carta-recusa');
			$('#popupMsgCorpo').css('width','410px');
			$('#popupMsgCorpo').css('height','295px');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}		
	else if (tipo == 'notificao-massiva') {
	$(document).ready(function(){
		var id_fatura = corpo;
		$('#popupMsgTitulo').html(titulo);
		$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
		$('#popupMsgCorpo').load('user-notify-pop-massiva-alert.php');
		$('#popupMsgCorpo').css('width','100%');
		$('#popupMsgCorpo').css('height','50%');
		$('#popupMsg').css('width','410px');
		$('#popupMsg').css('position','fixed');
		$('#popupMsg').css('top','50%');
		$('#popupMsg').css('left','50%');
		centerPopup();
		loadPopup();
	});		
	}
		else if (tipo == 'notificao-massiva-status-inscricao') {
		$(document).ready(function(){
			var id_fatura = corpo;
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html('<img src="/css-default/imgs/ajax-loader.gif" />');
			$('#popupMsgCorpo').load('user-notify-pop-massiva-alert.php?status=insc');
			$('#popupMsgCorpo').css('width','100%');
			$('#popupMsgCorpo').css('height','50%');
			$('#popupMsg').css('width','410px');
			$('#popupMsg').css('position','fixed');
			$('#popupMsg').css('top','50%');
			$('#popupMsg').css('left','50%');
			centerPopup();
			loadPopup();
		});		
	}	  else if (tipo == 'frame'){
		$(document).ready(function(){
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html(corpo);
			$('#popupMsg').css('width','803px');
			centerPopup();
			loadPopup();
		});
	} else if (tipo == 'instituicoes'){
		$(document).ready(function(){
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html(corpo);
			centerPopup();
			loadPopup();
			$('.botao').click(function(){
				var id = $(this).attr('id');
				//$('#autor_inst_'+id).append('<tr> <td> dddd </td> </tr>');
				disablePopup();
			});
			
		});
	} else if (tipo == 'busca_instituicoes') {
		$(document).ready(function(){
			centerPopup();
			loadPopup();	
		});
	} else{
		$(document).ready(function(){
			$('#popupMsgTitulo').html(titulo);
			$('#popupMsgCorpo').html(corpo);
			centerPopup();
			loadPopup();
			
			
		});
	}
}