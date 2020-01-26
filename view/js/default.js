$(function() {

	/* Function gèrantla recherche gobale d'une liste */

	function globalsearchList(){

		var aParam = {};
			aParam["search"] = $("input").val();
			
		window.location.href = "http://wishlistcnam.ddns.net/mylists?p="+aParam["search"];
	}
	/** Evenement JS */

    $( "#searchlist" ).on({
        click: function(){
         	globalsearchList();
        }
    });
});	
