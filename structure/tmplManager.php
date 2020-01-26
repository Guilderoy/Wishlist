<?php

/**
 *
 * Class TmplManager , gère les methodes permettant de charger différents contenus côtés client
 * Chargement de mes pages HTML, JS et CSS
 *
**/

class TmplManager{

	private $sPath;

	public function __construct($sPath){	
		$this->path = $sPath;
		$this->content = '';
	}

	// Function who loads an html page

	public function loadHtml(){
		ob_start ();
        	require 'view/html/'.$this->path.'/'.$this->path.'.php';
			$this->content = ob_get_contents();
        ob_end_clean();
    	return $this->content;
    }	

	public function loadCss(){
		$this->contentCss="<link rel='stylesheet' href='view/css/".$this->path."/".$this->path.".css'>";
    	return $this->contentCss;
    }	

	public function loadJs(){
		$this->contentJs="<script type='text/javascript' src='view/js/".$this->path."/".$this->path.".js'>";
		return $this->contentJs;
    }
	
}