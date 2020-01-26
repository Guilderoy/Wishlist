$(function() {
	$(".nav li").removeClass("active");
	$('#login').addClass('active');
	$(".progress").css("display","none");

	/* Gère l'affichage du callBack */

	function displayCallback(divResponse){
		
		lastDiv = $("div.response").find('.alert').prevObject[0].innerHTML;

   		if (lastDiv == "")
	   		$("div.response").append(divResponse);
		
		setTimeout(function() {
		    $('div.response').fadeOut('slow');
		    $('div.response').empty();
		}, 7000);
	}

	function displayLoadingbar(){
		setTimeout(function() {
			$(".progress").css("display","block");
		}, 500);
	}

	function hideLoadingbar(){
		setTimeout(function() {
			$(".progress").css("display","none");
		}, 1000);
	}

	/* Function gèrant la mise à jour d'un utilisateur */

	function onUpdateUser( aParam ) {

		displayLoadingbar();

	   	$.ajax({
	       url : '/myaccount/updateaccount',
	       type : 'POST',
	       data: aParam,
	       dataType:'JSON',
	       success : function(oResult, statut){
	            var divResponse="";
            	$("div.response").show();
            	hideLoadingbar();
	            
	            if(oResult.message != undefined)
	           	    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
	       
	       		else
					divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";
	      		
	      		displayCallback(divResponse);
	       },

	       error : function(oResult, statut, erreur){
	         	var divResponse="";
	         	hideLoadingbar();
 				$("div.response").show();
		
           	    divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";
       		  	displayCallback(divResponse);
	       },

	       complete : function(oResult, statut){
	       }
	    });
	}

	/* Function gèrant l'envoi d'invitations par mail */

	function sendInvit( aParam ) {

		displayLoadingbar();

	   	$.ajax({
	       url : '/login/invitation',
	       type : 'POST',
	       data: aParam,
	       dataType:'JSON',
	       success : function(oResult, statut){
	            var divResponse="";
            	$("div.response").show();
	            $(".progress").css("display","none");
	            
	            if(oResult.message != undefined)
	           	    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
	           
	       		else
					divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";
	      		hideLoadingbar();
	      		displayCallback(divResponse);
	       },

	       error : function(oResult, statut, erreur){
	         	var divResponse="";
 				$("div.response").show();
           	    divResponse = "<div class='alert alert-danger' role='alert'>Un problème est survenu lors de l'envoi</div>";
           	    hideLoadingbar();
       		  	displayCallback(divResponse);
	       },

	       complete : function(oResult, statut){

	       }
	    });
	}


	/************* Gestion des events JS *************/

	$( "form" ).on({
	    submit: function(event) {
	        var	aParam = []; 
			    aParam = $( this ).serializeArray();
			onUpdateUser(aParam);
			event.preventDefault();
	    }
	});

	$( "#sendinvit" ).on({
		click: function(event){
	        var	aParam = []; 
				aParam = $("form").serializeArray();
			sendInvit(aParam);
		}
	});	

	$( "#resetbtn" ).on({
		click: function(){
			$("form").trigger('reset');
		}
	});	

	/******************************************/
});	