<?php

namespace controller\login;

/** Controlleur qui appel mes modeles et effectue les traitements concernant la page login */

class login{

	private $aParam;

	public static function createAccount($aParam){
		
		$aParam=array();

		if(isset($_POST['firstname']) && !empty($_POST['firstname']))
			$aParam['firstname'] = $_POST['firstname'];
		else
			$aResponse['message_err']="Veuillez saisir un prénom";

		if(isset($_POST['lastname']) && !empty($_POST['lastname']))
			$aParam['lastname'] = $_POST['lastname'];
		else
			$aResponse['message_err']="Veuillez saisir un nom de famille";

		if(isset($_POST['username']) && !empty($_POST['username']))
			$aParam['username'] = $_POST['username'];
		else
			$aResponse['message_err']="Veuillez saisir un nom d'utilisateur";

		if(isset($_POST['email']) && !empty($_POST['email']))
			$aParam['email'] = $_POST['email'];
		else
			$aResponse['message_err']="Veuillez saisir une adresse mail";

		/** Check sur mot de passe **/

		if(isset($_POST['password']) && !empty($_POST['password'])){
			if(isset($_POST['passwordconfirm']) && !empty($_POST['passwordconfirm'])){
				if($_POST['password'] === $_POST['passwordconfirm'])
					$aParam['password'] = sha1($_POST['password'] . "meinsaltzz");
				else
					$aResponse['message_err']="Les mots de passe ne correspondent pas";
			}else
				$aResponse['message_err']="Veuillez confirmer votre mot de passe";
		}else
			$aResponse['message_err']="Veuillez saisir un mot de passe";
	
	// Si pas d'erreurs alors on insère en bdd + envoi du mail			
			
		if(empty($aResponse)){
			$aParam['urlenable'] = sha1($aParam['username'] . "meinsaltzz");
			$aResponse = \models\login\login::registerUser($aParam);
		}

		$sTo = $aParam['email'];
		
		$sSubject = "Création de votre compte sur Wishlist";

		$aPreferences = ["input-charset" => "UTF-8", "output-charset" => "UTF-8"];
		$sSubject = iconv_mime_encode("Sujet", $sSubject, $aPreferences);
		
		$sMessage = "Bonjour ". $aParam['firstname'] . " ".$aParam['lastname'].",</br></br></br>" ;
		$sMessage.= "Votre compte sur le site wishlistcnam.fr vient d'être créé !</br></br></br>" ;
		$sMessage.= "<strong>Votre identifiant:</strong> ".$aParam['username']."</br></br>";
		$sMessage.= "Pour activer votre compte veuillez confirmer la demande de création en cliquant sur ce lien <a href=".SITE_URL."login/createaccount/".$aParam['urlenable']."?uid=".$aParam['username'].">activer mon compte</a></br></br>";
		$sMessage.= "Votre compte sur le site wishlistcnam.fr vient d'être créé ! Pour en profiter,</br></br></br></br>" ;
		$sMessage.=  "L'équipe Christmas Wishlist,";

		$sHeaders = "Content-Type: text/html; charset=UTF-8\r\n";
		$sHeaders .= "From: Wishlist-cnam <guillaumebr.bruno@gmail.com> \r\n";

		/** Ensuite on envoi un mail pour activer le compte **/

		$oMail = new \mail($sTo,$sSubject,$sMessage,$sHeaders);
		$oMail->sendmail();

		if(!empty($aResponse))
			die(json_encode($aResponse));
	}


	public static function updateAccount($aParam){

		if(isset($_SESSION) && !empty($_SESSION)){

			if($aParam['username'] == $_SESSION['username']){

				$aParam=array();

				if(isset($_POST['firstname']) && !empty($_POST['firstname']))
					$aParam['firstname'] = $_POST['firstname'];
				else
					$aResponse['message_err']="Veuillez saisir un prénom";

				if(isset($_POST['lastname']) && !empty($_POST['lastname']))
					$aParam['lastname'] = $_POST['lastname'];
				else
					$aResponse['message_err']="Veuillez saisir un nom de famille";

				if(isset($_POST['username']) && !empty($_POST['username']))
					$aParam['username'] = $_POST['username'];
				else
					$aResponse['message_err']="Veuillez saisir un nom d'utilisateur";

				if(isset($_POST['email']) && !empty($_POST['email']))
					$aParam['email'] = $_POST['email'];
				else
					$aResponse['message_err']="Veuillez saisir une adresse mail";

				/** Check sur mot de passe **/

				if(isset($_POST['password']) && !empty($_POST['password'])){
					if(isset($_POST['passwordconfirm']) && !empty($_POST['passwordconfirm'])){
						if($_POST['password'] === $_POST['passwordconfirm'])
							$aParam['password'] = sha1($_POST['password'] . "meinsaltzz");
						else
							$aResponse['message_err']="Les mots de passe ne correspondent pas";
					}else
						$aResponse['message_err']="Veuillez confirmer votre mot de passe";
				}else
					$aResponse['message_err']="Veuillez saisir un mot de passe";
			
			// Si pas d'erreurs alors on insère en bdd + envoi du mail			
					
				if(empty($aResponse)){

					$aParam['urlenable'] = sha1($aParam['username'] . "meinsaltzz");
					$aResponse = \models\login\login::updateUser($aParam);

					$sTo = $aParam['email'];
				
					$sSubject = "Modification de votre compte sur Wishlist";

					$aPreferences = ["input-charset" => "UTF-8", "output-charset" => "UTF-8"];
					$sSubject = iconv_mime_encode("Sujet", $sSubject, $aPreferences);
					
					$sMessage = "Bonjour ". $aParam['firstname'] . " ".$aParam['lastname'].",</br></br></br>" ;
					$sMessage.= "Votre compte sur le site wishlistcnam.fr vient d'être modifié !</br></br></br>" ;
					$sMessage.= "<strong>Votre identifiant:</strong> ".$aParam['username']."</br></br>";
					$sMessage.=  "L'équipe Christmas Wishlist,";

					$sHeaders = "Content-Type: text/html; charset=UTF-8\r\n";
					$sHeaders .= "From: Wishlist-cnam <guillaumebr.bruno@gmail.com> \r\n";

					/** Ensuite on envoi un mail pour activer le compte **/

					$oMail = new \mail($sTo,$sSubject,$sMessage,$sHeaders);
					$oMail->sendmail();

					$aResponse['message'] = "Votre compte vient d'être mis à jour, vous avez reçu un mail de confirmation à l'instant";

					self::disconnectUser();
				}
			}else
				$aResponse['message_err'] = "Vous tentez de modifier un compte qui n'est pas le votre !";
		}else
			$aResponse['message_err'] = "Votre session a expiré";

		if(!empty($aResponse))
			die(json_encode($aResponse));
	}

	/** Fonction qui active le compte **/

	public static function enableAccount($aParam){

		$iHash = str_replace('login/createaccount/','',$aParam['url']);

		if(sha1($_GET['uid'] . "meinsaltzz") === $iHash){
			
			$aResponse = \models\login\login::enableUser($_GET['uid']);	
			if($aResponse){
				$aResponse["message"] = "Votre compte est maintenant activé";	
				return $aResponse["message"];
			}
		}
	}

	public static function sendInvitation($aParam){

		if ( preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $aParam['email_invit'] )){
			$sSubject = "Invitation sur wishlistcnam.fr";

			$aPreferences = ["input-charset" => "UTF-8", "output-charset" => "UTF-8"];
			$sSubject = iconv_mime_encode("Sujet", $sSubject, $aPreferences);
			
			$sMessage = "Bonjour,</br></br></br>" ;
			$sMessage.= "Votre amis vient de vous envoyer une invitation <a href='http://wishlistcnam.ddns.net'>Wishlistcnam</a> !</br></br></br>" ;
			$sMessage.= "Venez créer votre compte, vous pourrez consulter les listes de noel de vos amis, familles mais aussi en créer pour vous ! </br></br>";
			$sMessage.= "A très vite !! </br></br>";
			$sMessage.= "L'équipe Christmas Wishlist,";

			$sHeaders = "Content-Type: text/html; charset=UTF-8\r\n";
			$sHeaders .= "From: Wishlist-cnam <guillaumebr.bruno@gmail.com> \r\n";

			/** Ensuite on envoi un mail pour activer le compte **/

			$oMail = new \mail($aParam['email_invit'],$sSubject,$sMessage,$sHeaders);
			$oMail->sendmail();
			$aResponse['message'] = "Invitation envoyée à l'adresse ".$aParam['email_invit'];
		}else
			$aResponse["message_err"] = "Le format de l'adresse mail n'est pas respecté !! ";


		if(!empty($aResponse))
			die(json_encode($aResponse));
	}

	/** Fonction de login de l'utilisateur **/

	public static function connectUser($aParam){
		
		$aResponse = array();

		if(isset($aParam['usernamelog']) && !empty($aParam['usernamelog']))
			$aParam['username'] = $_POST['usernamelog'];

		if(isset($aParam['passwordlog']) && !empty($aParam['passwordlog']))
			$aParam['password'] = $_POST['passwordlog'];

		$aResult = \models\login\login::checkUser($aParam);
	
	    if ($aResult['user'] > 0) {

			if($aResult['enabled'] != 0) {

				session_id();
				$_SESSION['username'] = $aParam['usernamelog'];
				$_SESSION['password'] = $aParam['passwordlog'];
				$_SESSION['id_user'] = $aResult['id'];
				$_SESSION['start'] = time();
				$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

				$aResponse['message'] = "Vous êtes maintenant bien connecté, vous allez être redirigé vers la page d'accueil";
			}else
				$aResponse['message_err'] = "Vous devez confirmer la création de votre compte depuis l'email de confirmation avant de vous connecter";
		} else
		    $aResponse['message_err'] = "Désolé mais les informations de connexions saisies sont incorrectes";

		if(!empty($aResponse))
			die(json_encode($aResponse));
	}

	/** Fonction de deconnexion de l'utilisateur **/

	public static function disconnectUser(){
		if(session_destroy()){
			$aResponse['message'] = "Vous êtes maintenant déconnecté de votre compte";
			return $aResponse;
		}
	}
}

?>
