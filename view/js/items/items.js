//Au chargement de la page

$(function() {

	$(".nav li").removeClass("active");
	$('#accueil').addClass('active');


	var aParam = {};
	var iFlag = 3;
		aParam["limit"]=3;
		aParam["offset"]=3;	

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

	/* Bar de chargement */

	function displayLoadingbar(){
		setTimeout(function() {
		    $(".progress").css("display","block");
		}, 1000);
	}

	/* Cache la bar de chargement apres callback */

	function hideLoadingbar(){
      setTimeout(function() {
        $(".progress").css("display","none");
      }, 1000);
    }

	/* Fonction d'ajout d'un objet a une liste */

	function addToList(id_item){

		aParam = {};
		aParam['id_item'] = id_item;

		$('html').css('overflow','hidden');

        $( ".modal" ).dialog({
            width: 'auto',
            maxWidth: 1200,
            height: 'auto',
            modal: true,
            my: "center",
	        at: "center",
	        of: window,
            fluid: true,
            resizable: false,
            dialogClass: "no-close",
            buttons: [
                {
                    text: "Ajouter le nouvel objet",
                    click: function() {
                    	
            			displayLoadingbar();
                    	aParam['id_list'] = $('select[name=add_select]').val();
                    	
                        $.ajax({
                           url : '/items/addExistingItem',
                           type : 'POST',
                           data:  aParam,
                           dataType:'JSON',
                           success : function(oResult, statut){

							   /* Gestion de mon callback */

                                var divResponse="";
                                $("div.response").show();

                                /* Si retour ok */

                                if(oResult.message != undefined){
                                    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
                                    
                                    $("form").trigger('reset');
                                  
                                }
                                /** Sinon **/
                                else
                                    divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";

                                displayCallback(divResponse);
                           },

							/* Si erreur ajax dans le callback */

                           error : function(oResult, statut, erreur){
                                var divResponse="";
                                $("div.responseAdd").show();
                                divResponse = "<div class='alert alert-danger' role='alert'>"+erreur+"</div>";
                                $("div.responseAdd").append(divResponse);

                                displayCallback(divResponse);
                           },

							/* Quand transaction AJAX terminée  */

                           complete : function(oResult, statut){
                           		hideLoadingbar();
                                toTopBar();
                           		$(".progress").css("display","none");
                           		$('div.modal').dialog('close');
                           		$('html').css('overflow','visible');
                           }
                        });
                    }
                },
                {
                    text: "Fermer la fenêtre",
                    click:function(){
                    	toTopBar();
                    	$('html').css('overflow','visible');
                        $( this ).dialog( "close" );   
                    }
                }
            ]
        });
    }

	/* Fonction qui déroule plus d'articles sur la page d'accueil */

	function loadMoreItems(iReset){
		
		if(iReset === true){
			aParam["limit"]= 3;
			aParam["offset"]=6;
		}

		displayLoadingbar();

		$.ajax({
	       url : '/',
	       type : 'POST',
	       data: aParam,
	       dataType:'JSON',
	       success : function(oResult, statut){
   				var sHtml="";
   				$(".progress").css("display","none");
		        $.each(oResult, function( index, post) {
		        	sHtml += "<div class='col-lg-4 col-md-6 mb-4'>";
		        	sHtml += "<div class='card h-100'>";
		        	sHtml += "<a><img class='card-img-top' src='"+post.img+"'></a>";
				  	sHtml += "<div class='card-body'>";
				  	sHtml += "<h4 class='card-title'><a href='"+post.url+"' target='_blank' name='item_name'>"+post.name+"</a></h4>";
				  	sHtml += "<p class='card-text'>"+post.description+"</p></div>";
				  	sHtml += "<div class='card-footer'>";
				  	sHtml += "<a href='#' class='btn btn-success' name='item_ident' id="+post.id+"><i class='fas fa-heart'></i></a></div>";
				  	sHtml += "</div></div>";			  
				});
				$( "#rowappend.row" ).append(sHtml);
	       },

	       error : function(oResult, statut, erreur){
      			
	       },

	       complete : function(oResult, statut){
	  			$(".progress").css("display","none");
	       }
	    });
	}

	/* Fonction qui affiche le bouton go to top page */

	function displayScroll(){
	    if($(this).scrollTop() >= 50)
	        $('#return-to-top').fadeIn(200);
	    else
	        $('#return-to-top').fadeOut(200);
	}

	/* Fonction qui gere le go to top page */

	function toTopBar(){

		var iReset = true;

		$("#rowappend").empty().append(" ");
	    $('body,html').animate({
	        scrollTop : 0                       
	    }, 500);
	   	
	    loadMoreItems(iReset);
	}
	
	/* Gestion des Events JS */

	$(window).scroll(function(e) {

	    if($(window).scrollTop() == $(document).height() - $(window).height()) {
			aParam["offset"]+=iFlag;
			loadMoreItems(e);
	    }

	    // Fait apparaitre le button pour remonter en haut de page
	    displayScroll();

	});

	$( "#return-to-top" ).on({
		click: function(){
			toTopBar();
		}
	});

	$(document).on('click',"a[name='item_ident']",function(e){
		var id_item = e.target.id;
					  e.preventDefault();
		addToList(id_item);
	});

});
