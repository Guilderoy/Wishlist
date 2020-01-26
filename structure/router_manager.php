<?php

/** Class router_manager , gère les différentes méthodes d'appels https depuis l'url et renvoi une correspondance */

class router_manager{

	private $sUrl;
	private $sRoute = array();

	public function __construct($sUrl){
	 	$this->url = $sUrl;
	}

	public function get($sPath,$sCall){
		$sRoute = new route($sPath,$sCall);
		$this->route['GET'][] = $sRoute;
		return $sRoute;
	}

	public function post($sPath,$sCall){
		$sRoute = new route($sPath,$sCall);
		$this->route['POST'][] = $sRoute;
		return $sRoute;
	}

	public function exec(){
		
		if(!isset($this->route[$_SERVER['REQUEST_METHOD']])){
			throw new Exception('Requête inexistante');
		}
		foreach($this->route[$_SERVER['REQUEST_METHOD']] as $route){
			if($route->match($this->url)){
				return $route->request();
			}
		}

		return '404';
		
	}

}



?>