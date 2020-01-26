<?php
    session_start();
    //header("Content-Type: text/html;charset=UTF-8");

    // Chargement de l'autoloader 

    require 'structure/autoloader.php';

    /** Initialisation des variables **/ 

    $sPage='';
    $content='';
    $sStyle='';
    $sJavascript='';
    $bConnected = false;

    Autoloader::register();
    $bConnected = isConnected::checkSession();

    define("SITE_NAME",$_SERVER['SERVER_NAME']);
    define("SITE_URL","http://wishlistcnam.ddns.net/");

    $now = time(); 

    if(isset($_SESSION['expire']) && !empty($_SESSION['expire'])){
        if ($now > $_SESSION['expire']){ 
            session_destroy();
        } 
    }

    if(isset($_GET['url']) and !empty($_GET['url'])){
        $sUrl = $_GET['url'];
    }else{
        $sUrl = "";
    }

    /* Gestion de nos routes en fonction de l'URL saisi */

    $oUrl = new urlHandler($sUrl);
    $sPage  = $oUrl->parseUrl();

    // On charge les pages Css et Js dont nous aurons besoin pour chacune de nos pages appelées

    $oTmpl = new tmplManager($sPage);

    $sContent=$oTmpl->loadHtml();
    $sStyle=$oTmpl->loadCss();  
    $sJavascript=$oTmpl->loadJs();

    /** Template HTML chargé par défaut **/

    require 'view/default.html';
 ?>
