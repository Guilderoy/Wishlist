<?php

/** Class urlHandler , Appel mes différents controller en fonction de l'URL saisi */

class urlHandler{

	private $url;

	public function __construct($sUrl){

		$this->url = $sUrl;
	}

	public function parseUrl(){

		// Gestion des routes depuis l'URL

	    $router = new router_manager($this->url); 
	 
	    /************* Toutes le requetes qui passent en GET **************/

	    $router->get('/',function($id){
	    	return 'items';
	    	die();
	    });

	    $router->get('/myaccount',function($id){ 
	        if(isset($_SESSION['username']))
	            return 'myaccount';
	        else
	            return 'login';
	    });

	    $router->get('/items',function($id){ return 'items';});

	    /* Chargement d'une liste par défaut pour le user */

	    $router->get('/mylists',function($id){
	    	if(isset($_SESSION['username'],$_SESSION['id_user'])){
	    		\controller\mylists\mylists::getLists('all',$_GET['p']);
	    		return 'mylists';
	    	}else
	    		return 'login';
	    	die();
	    });

	    $router->get('/deconnexion',function($id){ 
	        $aResponse = \controller\login\login::disconnectUser();
	         header('Location: http://wishlistcnam.ddns.net/');
	    }); 

	    $router->get('/login/createaccount/:id', function($id){ 
	        $aResponse = \controller\login\login::enableAccount($_GET);
	        header('Location: http://wishlistcnam.ddns.net/');
	    } ); 

	    /*******************************************************************/

	    /* Toutes les requetes qui passent en POST */

	    $router->post('/login/createaccount',function($id){ 
	        \controller\login\login::createAccount($_POST);
	        die();          
	    });

	    $router->post('/myaccount/updateaccount',function($id){ 
	        \controller\login\login::updateAccount($_POST);
	        die();   
	    });

	    $router->post('/login/invitation',function($id){ 
	        \controller\login\login::sendInvitation($_POST);
	        die();   
	    });


	    /* Connexion de l'utilisateur */

	    $router->post('/login',function($id){ 
	        \controller\login\login::connectUser($_POST);
	        die();
	    });

	    /* Appel pour charger plus d'articles */

	    $router->post('/',function($id){ 
	       $oItems = \controller\items\items::getItems($_POST);
           if(!empty($oItems))
       			die(json_encode($oItems));
       		return 'items';
	    });

	    /* Chargement des lists */

	    $router->post('/mylists/create',function($id){
	    	if(isset($_SESSION['id_user'])){
	    		$aResponse = \controller\mylists\mylists::addList($_POST,$_SESSION['id_user']);
           		if(!empty($aResponse))
       				die(json_encode($aResponse));
	    	}
	    	else
	    		return 'login';
	    	die();
	    });


	    /* Chargement d'une liste spécifique*/

	    $router->post('/selectlist',function($id){

	    	if(isset($_SESSION['username'],$_SESSION['id_user'])){		
			
    		   $aResult = \controller\mylists\mylists::getItemsFromIDList($_POST['id_list'],'');
    		   $aResult[0]['session_user'] = $_SESSION['id_user'];

    		   die(json_encode($aResult));
	    	}else{
	    		return 'login';
	    	}
	    });

	    /***   Modification d'un items dans une liste   ***/

	    $router->post('/mylists/removeList',function($id){
			$aResult = \controller\mylists\mylists::removeList($_POST);
			if(!empty($aResult))
				die(json_encode($aResult));
	    	die();
	    });

	    /** Rafraichis le select des listes **/

	    $router->post('/items/refreshselect',function($id){

			$aResult = \controller\mylists\mylists::getLists('all');
			if(!empty($aResult))
				die(json_encode($aResult));
	    });

	    /***     Ajoute une items à une liste              ***/

	    $router->post('/items/addItems',function($id){
			$aResult = \controller\mylists\mylists::addItemToList($_POST,$_FILES);
			if(!empty($aResult))
				die(json_encode($aResult));
	    	die();
	    });

     	/***   Réservation d'un item dans une liste  par un user  ***/

	    $router->post('/items/reserveItem',function($id){
			$aResult = \controller\mylists\mylists::reserveItem($_POST);
			if(!empty($aResult))
				die(json_encode($aResult));
			die();
	    });

     	/***   Export au format PDF       ***/

	    $router->post('/mylists/exportToPDF',function($id){
			$aResult = \controller\mylists\mylists::exportToPDF($_POST);
			die($aResult);
	    });

	    /***   Suppression d'un item dans une liste             ***/

	    $router->post('/items/deleteItems',function($id){
			$aResult = \controller\mylists\mylists::deleteItemToList($_POST);
			if(!empty($aResult))
				die(json_encode($aResult));
			die();
	    });

	     /***   Suppression d'un item dans une liste             ***/

	    $router->post('/items/addExistingItem',function($id){
			$aResult = \controller\mylists\mylists::addExistingItem($_POST);
			if(!empty($aResult))
				die(json_encode($aResult));
			die();
	    });

	    /*** Execute le routage ***/

	    return $router->exec(); 

	}

}


?>
