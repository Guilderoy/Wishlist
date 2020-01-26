$(document).ready(function() {
    
    hideLoadingbar();
    $(".nav li").removeClass("active");
    $('#mylists').addClass('active');
    $( ".modal" ).hide();

    var aParam = {};

    // Initialisation de mon tableau d'objets

    var dataTable =  $('.table.table-striped').DataTable( {
        "paging":   false,
        "language": {
            "url": "../../../medias/libraries/datatables/i18n/lang_fr"
        }
    } );

    selectList();

    $('#addItem').fadeIn();

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

    // Function gèrant la création d'une liste

    function onCreateList( aParam ) {

        displayLoadingbar();

        $.ajax({
           url : '/mylists/create',
           type : 'POST',
           data : aParam,
           dataType:'JSON',
           success : function(oResult, statut){
                var divResponse="";
                $(".response").show();

                if(oResult.message != undefined)
                    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
                else
                    divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";
                $("form").trigger('reset');
                reloadAjax();
                displayCallback(divResponse);
           },

           error : function(oResult, statut, erreur){
                var divResponse="";
                $("div.response").show();
                divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de la création de la liste</div>";
                displayCallback(divResponse);
           },

           complete : function(oResult, statut){
                hideLoadingbar();
           }
        });
    }

    // Function gèrant la selection d'une liste

    function selectList(){

        aValue = [];
        displayLoadingbar();
        aParam['id_list'] = $( "#controlselect option:selected" ).val();

        $.ajax({
           url : '/selectlist',
           type : 'POST',
           data : aParam,
           dataType:'JSON',
           success : function(oResult, statut){

            /* On vide notre tableau dataTable */

            var dataTable = $('.table.table-striped').DataTable();
            dataTable.clear();

            /* On le rempli avec les nouvelles valeurs */

            var session_user = oResult[0].session_user;

            $.each(oResult, function( index, post) {

              if(post.id_item != undefined){
                post.img ="<img class='img-preview' src="+post.img+" alt='preview'>";
                post.url ="<a href="+post.url+" title='Acheter' target='_blank\'>";
                post.url +="<i class='fab fa-amazon'></i>";
                post.url +="</a>";

                if(session_user != post.id_user_list ){

                  post.url +="<a href='#' title='Réserver'>";

                    post.url +="<i class='fas fa-user-check'></i>";

                  post.url +="</a>";

                  if(post.firstname == null)
                      post.firstname = "Pas encore réservé";
                }else{
                    post.url +="<a href='#' title='Supprimer'>";
                    post.url +="<i class='far fa-trash-alt'></i>";
                    post.url +="</a>";
                }

                aValue = [post.id_item,
                          post.id,
                          post.img,
                          post.name,
                          post.firstname,
                          post.url ];

                dataTable.row.add(aValue);
                dataTable.column(0).visible(false);
                dataTable.column(1).visible(false);
              }
            });
            dataTable.draw();
            $('td').addClass('lead');
          },
          error : function(oResult, statut, erreur){
              var divResponse="";
                $("div.response").show();
                divResponse = "<div class='alert alert-danger' role='alert'>Aucune liste récupérée pour cette recherche </div>";
                $("div.response").append(divResponse);

                setTimeout(function() {
                    $('div.response').fadeOut('slow');
                    $('div.response').empty();
                }, 7000);
           },
          complete : function(oResult, statut){
              hideLoadingbar();
          }
        });
    }

    // Function qui rafraichis le contenu

    function reloadAjax(){

        $.ajax({
            url : '/items/refreshselect',
            type : 'POST',
            data : aParam,
            dataType:'JSON',
            success : function(oResult, statut){
                $("option").remove();
                var divResponse = '';

                $.each(oResult, function( index, post) {
                    divResponse += "<option value="+post.id+">"+post.name+" ("+post.firstname+")"+"</option>";
                });

                $("select").append(divResponse);
                selectList();
            },
        });
    }

    // Function qui ajoute un objet sur une liste

    function addItem(){


        $( ".modal" ).dialog({
            width: 'auto',
            maxWidth: 1200,
            height: 'auto',
            modal: true,
            fluid: true,
            resizable: false,
            dialogClass: "no-close",
            buttons: [
                {
                    text: "Ajouter le nouvel objet",
                    click: function(e) {
                    e.preventDefault();
                    displayLoadingbar();
                    var aParam = new FormData();
                        aForm = $('form').serializeArray();

                        $.each(aForm, function( index, post) {
                            aParam[index] = post;
                        });

                        files = $('#addimg_upload')[0].files[0];

                        aParam.append('file',files);
                        aParam.append('item_name',$('input[name=itemname]').val());
                        aParam.append('add_select',$('select[name=add_select]').val());
                        aParam.append('description',$('textarea[name=description]').val());
                        aParam.append('linkurl',$('input[name=linkurl]').val());

                        $.ajax({
                           url : '/items/addItems',
                           type : 'POST',
                           data:  aParam,
                           processData: false,
                           contentType: false,
                           dataType:'JSON',
                           success : function(oResult, statut){
                                var divResponse="";
                                $("div.response").show();
                                /* Si retour ok */
                                if(oResult.message != undefined){
                                    divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
                                    $('div.modal').dialog('close')
                                    $("form").trigger('reset');
                                    selectList();
                                }
                                /** Sinon **/
                                else
                                    divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";

                               displayCallback(divResponse);
                           },

                           error : function(oResult, statut, erreur){
                                var divResponse="";
                                $("div.response").show();
                                divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de la création de la liste</div>";
                                displayCallback(divResponse);
                           },

                           complete : function(oResult, statut){
                              hideLoadingbar();
                           }
                        });
                    }
                },
                {
                    text: "Fermer la fenêtre",
                    click:function(){
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
    }

    // Function qui supprime un objet sur une liste

    function removeItem(aParam){

        if (confirm("Etes vous sûr de vouloir supprimer cet objet de votre liste ?")){

                displayLoadingbar();

                $.ajax({
                   url : '/items/deleteItems',
                   type : 'POST',
                   data : aParam,
                   dataType:'JSON',
                   success : function(oResult, statut){
                        var divResponse="";
                        $("div.responseRemove").show();

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
                        divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de la création de la liste</div>";
                        displayCallback(divResponse);
                   },

                   complete : function(oResult, statut){
                      hideLoadingbar();
                   }
                });
        }
    }

    // Function qui reserve un objet sur une liste

    function reserveItem(aParam){

        if(confirm("Etes vous sûr de vouloir réserver cet objet ?")){

           displayLoadingbar();

                $.ajax({
                   url : '/items/reserveItem',
                   type : 'POST',
                   data:  aParam,
                   dataType:'JSON',
                   success : function(oResult, statut){
                        var divResponse="";
                        $("div.response").show();

                        if(oResult.message != undefined){
                            selectList();
                            divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
                        }
                        else
                            divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";

                       displayCallback(divResponse);
                   },

                   error : function(oResult, statut, erreur){
                        var divResponse="";
                        $("div.response").show();
                        divResponse = "<div class='alert alert-danger' role='alert'>Pas d'objet présent dans cette liste</div>";
                        $("div.response").append(divResponse);

                        setTimeout(function() {
                            $('div.response').fadeOut('slow');
                            $('div.response').empty();
                        }, 7000);

                   },

                   complete : function(oResult, statut){
                        hideLoadingbar();
                   }
                });
        }
    }

    // Function qui gère la suppression d'une liste

    function removeList(){

        aParam = {};
        aParam['id_list'] = $( "#controlselect option:selected" ).val();

        if (confirm("Etes vous sûr de vouloir supprimer la liste sur laquelle vous êtes actuellement")){
                displayLoadingbar();
                $.ajax({
                   url : '/mylists/removeList',
                   type : 'POST',
                   data:  aParam,
                   dataType:'JSON',
                   success : function(oResult, statut){
                        var divResponse="";
                        $("div.response").show();
                        hideLoadingbar();

                        if(oResult.message != undefined){
                            reloadAjax();
                            divResponse = "<div class='alert alert-success' role='alert'>"+oResult.message+"</div>";
                        }

                        else
                            divResponse = "<div class='alert alert-danger' role='alert'>"+oResult.message_err+"</div>";

                        displayCallback(divResponse);
                   },

                   error : function(oResult, statut, erreur){
                        var divResponse="";
                        $("div.response").show();
                        divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de la suppression de la liste</div>";
                        $("div.response").append(divResponse);

                        setTimeout(function() {
                            $('div.response').fadeOut('slow');
                            $('div.response').empty();
                        }, 7000);
                   },
                   complete : function(oResult, statut){
                      hideLoadingbar();
                   }
                });
        }

    }

    // Function qui gère les exports PDF

    function exportToPDF(){

        var aParam = {};
        aParam['id_list'] = $( "#controlselect option:selected" ).val();

        if (confirm("Voulez-vous vraiment exporter cette liste en PDF ?")){

                displayLoadingbar();

                $.ajax({
                   url : '/mylists/exportToPdf',
                   type : 'POST',
                   data:  aParam,
                   cache: false,
                   success : function(oResult, statut){
                       // Pour palier à la suppression du navigateur Google de la "Top navigation" je passe dans un iframe
                       let oPdf = window.open("");
                           oPdf.document.write("<iframe width='100%' height='100%' src='data:application/pdf;base64, " + encodeURI(oResult)+"'></iframe>");
                   },

                   error : function(oResult, statut, erreur){
                        var divResponse="";
                        $("div.response").show();
                        divResponse = "<div class='alert alert-danger' role='alert'>Erreur lors de l'export de la liste</div>";
                       displayCallback(divResponse);
                   },

                   complete : function(oResult, statut){
                      hideLoadingbar();
                   }
                });
        }
    }

    /************* Gestion des events JS *************/

    $( "form" ).on({
        submit: function(e) {
            aParam['listname'] = $('input[name=listname]').val();

            onCreateList(aParam);
            e.preventDefault();
        }
    });

    // Permet la suppression d'une ligne 

    $('tbody').on( 'click', '.far.fa-trash-alt', function (e) {

      var iIndex = ($(this).parents('tr').index());

          aParam['id_item'] = $('.table.table-striped').DataTable().rows().data()[iIndex][0];
          aParam['id_list'] = $('.table.table-striped').DataTable().rows().data()[iIndex][1];

          $('.table.table-striped').DataTable().row( $(this).parents('tr') ).remove().draw();
          removeItem(aParam);
          e.preventDefault();
    });

    // Reservation d'un item sur un liste 

    $('tbody').on( 'click', '.fas.fa-user-check', function (e) {

      var iIndex = ($(this).parents('tr').index());

          aParam['id_item'] = $('.table.table-striped').DataTable().rows().data()[iIndex][0];
          aParam['id_list'] = $('.table.table-striped').DataTable().rows().data()[iIndex][1];
          reserveItem(aParam);
          e.preventDefault();
    });



    $( "#deleteList" ).on({
        click: function(e) {
            removeList();
        }
    });

    $( "#resetbtn" ).on({
        click: function(){
            $("form").trigger('reset');
        }
    });


    $( "select" ).on( "change", function(e) {
        selectList();
        e.preventDefault();
    });

    $( "#resetbtn" ).on({
        click: function(){
            $("form").trigger('reset');
        }
    });

    $( "#addItem" ).on({
        click: function(){
            addItem();
        }
    });

    $( ".export" ).on({
        click: function(e){

            exportToPDF();
            e.preventDefault();
        }
    });

} );