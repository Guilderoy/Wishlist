<?php

/**
 * Class Autoloader qui charge automatiquement mes classes
 */

class Autoloader{

    /**
     * Enregistre l'autoloader
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Gère le chargement des classes automatiquement
     */

    static function autoload($sClass){
        
	$sClass = str_replace('\\', '/', $sClass);
        if(file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$sClass.'.php')){
            require $_SERVER["DOCUMENT_ROOT"].'/'.$sClass.'.php';
        }else
            require __DIR__.'/'.$sClass.'.php';
    }

}

?>
