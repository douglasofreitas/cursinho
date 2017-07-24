//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatusEd = 0;
//loading popup with jQuery magic!
function loadPopupEd(){
	//loads popup only if it is disabled
	if(popupStatusEd==0){
		$("#backgroundPopupEd").css({
			"opacity": "0.7"
		});
		$("#backgroundPopupEd").fadeIn(400);
		$("#popupMsgEd").fadeIn(400);
		popupStatusEd = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopupEd(){
	//disables popup only if it is enabled
	if(popupStatusEd==1){
		$("#backgroundPopupEd").fadeOut(400);
		$("#popupMsgEd").fadeOut(400);
		popupStatusEd = 0;
	}
}

//centering popup
function centerPopupEd(){
	//request data for centering
	var windowWidthEd = document.documentElement.clientWidth;
	var windowHeightEd = document.documentElement.clientHeight;
	var popupHeightEd = $("#popupMsgEd").height();
	var popupWidthEd = $("#popupMsgEd").width();
	//centering
	$("#popupMsgEd").css({
		"position": "fixed",
		"top": windowHeightEd/2-popupHeightEd/2,
		"left": windowWidthEd/2-popupWidthEd/2
	});
	//only need force for IE6
	$("#backgroundPopupEd").css({
		"height": windowHeightEd
	});
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	//LOADING POPUP
	//Click the button event!
	$("#buttonEd").click(function(){
		//centering with css
		centerPopupEd();
		//load popup
		loadPopupEd();
	});
	//CLOSING POPUP
	//Click the x event!
	$("#popupMsgCloseEd").click(function(){
		disablePopupEd();
	});
	//Click out event!
	$("#backgroundPopupEd").click(function(){
		disablePopupEd();
	});
	$("#terminarEdicao").click(function(){
		disablePopupEd();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatusEd==1){
			disablePopupEd();
		}
	});
});
// função que chama o popupMsgEd
function popupMsgEd () {
	$(document).ready(function(){
		centerPopupEd();
		loadPopupEd();			
	});
	return false;	
}