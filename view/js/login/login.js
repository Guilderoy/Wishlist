//Au chargement de la page

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

	// Function d'enregistrement d'un compte utilisateur

	function onRegisterSubmit( aParam ) {

		displayLoadingbar();

	   	$.ajax({
	       url : '/login/createaccount',
	       type : 'POST',
	       data: aParam,
	       dataType:'JSON',
	       success : function(oResult, statut){
	            var divResponse="";
				hideLoadingbar();
				 
            	$("div.response").show();

	            if(oResult.message != undefined){
	           	    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
	            }
	           	
	       		else
					divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";


	      		displayCallback(divResponse);

	       },

	       error : function(oResult, statut, erreur){
	         	var divResponse="";
 				$("div.response").show();
 				hideLoadingbar();
           	    divResponse = "<div class='alert alert-danger' role='alert'>Compte déjà existant veuillez changer l'adresse mail ou le nom d'utilisateur</div>";
 
       		  	displayCallback(divResponse);
	       },

	       complete : function(oResult, statut){
	       		hideLoadingbar();
	       }
	    });
	}

	// Function d'appel lors de la connexion d'un user

	function onConnect(aParam){

		displayLoadingbar();

		$.ajax({
	       url : '/login',
	       type : 'POST',
	       data: aParam,
	       dataType:'JSON',
	       success : function(oResult, statut){
				$("div.response").show();
	            hideLoadingbar();
	            if(oResult.message != undefined){
	           	    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
	            	setTimeout(function() {
	            		window.location = "http://wishlistcnam.ddns.net/";
					}, 2000);	            		
	            }
	       		else
					divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";
	      		
	      		displayCallback(divResponse);
	       },

	       error : function(oResult, statut, erreur){
	         	var divResponse="";
	         	hideLoadingbar();
 				$("div.response").show();
           	    divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de la connexion à votre compte</div>";
       		  	displayCallback(divResponse);
       		  	$(".progress").css("display","none");
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
			onRegisterSubmit(aParam);
			event.preventDefault();
	    }
	});

	$( "#resetbtn" ).on({
		click: function(){
			$("form").trigger('reset');
		}
	});	


	$( "#connect" ).on({
		click: function(event){
	        var	aParam = []; 
				aParam = $("form").serializeArray();
			onConnect(aParam);
		}
	});	

	/******************************************/
});
