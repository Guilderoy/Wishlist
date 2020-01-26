<?php

/** Class route qui contient les methodes de correspondance à mes URLs pour le routeur */

class route{
	
	private $path;
	private $url;
	private $matches;

	public function __construct($sPath,$sCallable){
		$this->path = trim($sPath, '/');
		$this->callable = $sCallable;
	}

	public function match($url){
        $path = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";

        if(!preg_match($regex, $url, $matches)){
            return false;
        }

        $this->matches = $matches;
        return true;
    }

    // @return result from anonymous function in index.php
    
    public function request(){
    	return call_user_func_array($this->callable, $this->matches);
	}
}

?>