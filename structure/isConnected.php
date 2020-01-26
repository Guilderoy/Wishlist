<?php

/** Class isConnected qui vérifie si une session est active sur le site */

class isConnected{

	public static function checkSession(){

		if (isset($_SESSION['username']) && !empty($_SESSION['username']) ) {
			$aUser = \models\login\login::checkUser($_SESSION);	
			if($aUser['user'] === '1'){
				return $bConnected = true;
			}
		}
	}
}	

?>
