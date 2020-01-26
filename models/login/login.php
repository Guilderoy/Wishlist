<?php

namespace models\login;

/** Controlleur qui appel mes modeles et effectue les traitements concernant la page login */

class login{

	private $aBind;


	/***  Fonction enregistrement du compte **/

	public static function registerUser($aParam){

		$aBind = array();

		if(isset($aParam['firstname']) && !empty($aParam['firstname']))
			$aBind['firstname'] = $aParam['firstname'];
		if(isset($aParam['lastname']) && !empty($aParam['lastname']))
			$aBind['lastname'] = $aParam['lastname'];
		if(isset($aParam['username']) && !empty($aParam['username']))
			$aBind['username'] = $aParam['username'];
		if(isset($aParam['email']) && !empty($aParam['email']))
			$aBind['email'] = $aParam['email'];
		if(isset($aParam['password']) && !empty($aParam['password']))
			$aBind['password'] = $aParam['password'];
	
		$sSql ="INSERT INTO wl_users (firstname,lastname,username,email,password) 
				VALUES (:firstname,:lastname,:username,:email,:password)";

		$bRes = \coredb::getDB()->prepare($sSql,$aBind);


		if($bRes === true)
			$aResponse["message"]="Vous avez reçu un e-mail à l'adresse ".$aParam['email']."</br> Merci de confirmer l'enregistrement de votre compte";
		else
			$aResponse["message_err"]="Une erreur est survenue lors de l\'enregistrement du compte, merci de vérifier votre saisie";

		return $aResponse;
	}

	/***  Fonction enregistrement du compte **/

	public static function updateUser($aParam){

		$aBind = array();

		if(isset($aParam['firstname']) && !empty($aParam['firstname']))
			$aBind['firstname'] = $aParam['firstname'];
		if(isset($aParam['lastname']) && !empty($aParam['lastname']))
			$aBind['lastname'] = $aParam['lastname'];
		if(isset($aParam['username']) && !empty($aParam['username']))
			$aBind['username'] = $aParam['username'];
		if(isset($aParam['email']) && !empty($aParam['email']))
			$aBind['email'] = $aParam['email'];
		if(isset($aParam['password']) && !empty($aParam['password']))
			$aBind['password'] = $aParam['password'];
	
		$sSql ="UPDATE wl_users
				SET firstname =:firstname,
					lastname=:lastname,
					email=:email,
					password=:password
				WHERE username = :username";

		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		if($bRes === true)
			$aResponse["message"]="Votre compte a été modifié ! Vous avez reçu un e-mail à l'adresse ".$aParam['email']."</br>";
		else
			$aResponse["message_err"]="Une erreur est survenue lors de la modification du compte, merci de vérifier votre saisie";

		return $aResponse;
	}


	/** Fonction activation du compte **/

	public static function enableUser($sUsername){

		$aBind = array();

		if(isset($sUsername) && !empty($sUsername))
			$aBind['username'] = $sUsername;
	
		$sSql ="UPDATE wl_users SET enabled = '1' WHERE username=:username";

		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		if($bRes === true)
			$aResponse["message"]="Votre compte est maintenant activé";
		else
			$aResponse["message_err"]="Une erreur est survenue lors de l'activation";

		return $aResponse;
	}

	/** Fonction de vérification d'un compte existant **/

	public static function checkUser($aParam){

		$aBind = array();

		if(isset($aParam['username']) && !empty($aParam['username']))
			$aBind['username'] = $aParam['username'];
		if(isset($aParam['password']) && !empty($aParam['password']))
			$aBind['password'] = sha1($aParam['password'].'meinsaltzz');
		
		$sSql ="SELECT COUNT(*) as user, id,enabled FROM wl_users WHERE username=:username AND password=:password";

		$oRes = \coredb::getDB()->prepare($sSql,$aBind,'simple');

		return $oRes;
	}

		/** Fonction de vérification d'un compte existant **/

	public static function getAllUser($aParam){

		$aBind = array();

		if(isset($aParam['username']) && !empty($aParam['username']))
			$aBind['username'] = $aParam['username'];
		if(isset($aParam['password']) && !empty($aParam['password']))
			$aBind['password'] = sha1($aParam['password'].'meinsaltzz');
		
		$sSql ="SELECT * FROM wl_users WHERE username=:username AND password=:password";

		$oRes = \coredb::getDB()->prepare($sSql,$aBind,'objectlist');

		return $oRes;
	}

}

?>