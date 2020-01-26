<?php

namespace controller\mylists;

/** Controlleur qui appel mes modeles et effectue les traitements concernant la page lists */

class mylists{
	
	public static function getItemsLastList($iUser){

		if(isset($iUser) && !empty($iUser)){ 
			$oResult = \models\mylists\mylists::getItemsLastList($iUser);
			return $oResult;
		}else
			$aResponse['message_err'] = "Absence de paramètres id_user";

		return $aResponse;
	}

	public static function getItemsFromIDList($iList,$iItem=''){

		if(isset($iList) && !empty($iList))
			$aResult = \models\mylists\mylists::getItemsFromIDList($iList,$iItem);
		else
			$aResponse['message_err'] = "Absence du paramètre liste";

		return $aResult;
	}

	public static function addExistingItem($aParam){

		$bInsert = true;

		if(isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])){

			if(isset($aParam['id_list']) && !empty($aParam['id_list'])){ 
				if(isset($aParam['id_item']) && !empty($aParam['id_item'])){

					$aRes = \models\mylists\mylists::getItemsFromIDList($aParam['id_list'],$aParam['id_item']);

					if(isset($aRes) && !empty($aRes)){
						if($aRes[0]['id_item'] == $aParam['id_item']){
							$bInsert = false;
						} 
					}else
						$aResponse["message_err"] = "Erreur lors de la récupération des informations de liste";
					
					if($bInsert)
						$bRes = \models\mylists\mylists::addExistingItem($aParam);
	
					if(isset($bRes))
						$aResponse["message"] = "Objet ajouté à la liste";
					else
						$aResponse["message_err"] = "L'objet est déjà présent dans votre liste !";
				}		
			}else
				$aResponse["message_err"] = "Absence de paramètres pour l'ajout";
		}else
			$aResponse["message_err"] = "Veuillez vous connecter avant tout !";
		
		return $aResponse;
	}

	/** Réservation d'un objet sur une liste **/ 

	public static function reserveItem($aParam){

		if(isset($_SESSION['id_user']) AND !empty($_SESSION['id_user'])){

			if(isset($aParam['id_list']) && !empty($aParam['id_list'])){ 
				
				if(isset($aParam['id_item']) && !empty($aParam['id_item'])){
					
					$aRes =  \models\items\items::getReservedItem($aParam);

					if(empty($aRes['reserved']) || $aRes['reserved'] == NULL){
						
						$bRes = \models\items\items::reserveItem($aParam,$_SESSION['id_user']);	

						if($bRes)
							$aResponse["message"] = "Vous venez de réserver cet objet";
						else
							$aResponse["message_err"] = "Erreur lors de la réservation de cet objet";
					}
					else{
						// Si la personne ayant réservé l'objet clic à nouveau sur reserver -> on libère la réservation
			
						if($aRes['reserved'] == $_SESSION['id_user']){

							$bRes1 = \models\items\items::releaseItem($aParam,NULL);
							
							if($bRes1)
								$aResponse["message"] = "La réservation à été annulée ";
							else
								$aResponse["message_err"] = "Erreur lors de l'annulation de réservation";
						}
						else
							$aResponse["message_err"] = "Objet déjà réservé !";
					}
				}
			}else
				$aResponse["message_err"] = "Absence de paramètres pour la réservation";		
		}else
			$aResponse["message_err"] = "Veuillez vous connecter avant tout !";

		return $aResponse;
	}


	public static function addItemToList($aParam,$aFile){

		$aResponse=array();

			if(isset($aParam['item_name'],$aParam['add_select'],$aFile['file'],$aParam['description'])){ 

				if(!empty($aParam['item_name']) && !empty($aParam['add_select']) && !empty($aFile['file']) && !empty($aParam['description'])){

						$sName = $_FILES['file']['name'];
						$aParam['fileurl'] ="/medias/articles/".basename($sName);

						$sImgType = strtolower(pathinfo($aParam['fileurl'],PATHINFO_EXTENSION));

						$aType = array("gif","jpeg","png","jpg");

						  // Check extension
						if( in_array($sImgType,$aType) ){

						  	if(isset($aParam['linkurl'],$aParam['description']) && !empty($aParam['linkurl']) && !empty($aParam['description'])) {

                                    $aResult = \models\mylists\mylists::addItemToList($aParam);
                                    if ($aResult) {
                                        $bResult = move_uploaded_file($_FILES['file']['tmp_name'], "/var/www/html/wishlist" . $aParam['fileurl']);
                                        $aResponse['message'] = "Votre objet a bien été ajouté";
                                    } else
                                        $aResponse['message_err'] = "Erreur lors de l'ajout de votre objet dans la base";

						  	}else
					    		$aResponse['message_err'] = "Champ(s) manquant(s) ou incorrecte(s)";
					  	}else
                            $aResponse['message_err'] = "Seuls les formats \"gif\",\"jpeg\",\"png\",\"jpg\" sont acceptés";
				}else
					$aResponse['message_err'] = "Champ(s) vide veuillez le remplir";
			}else
				$aResponse['message_err'] = "Champs manquants lors de l'ajout, veuillez ressayer";

		return $aResponse;
	}	


	public static function deleteItemToList($aParam){

		if(isset($aParam['id_list']) && !empty($aParam['id_list'])){
			if(isset($aParam['id_item']) && !empty($aParam['id_item'])){
				$bResult = \models\mylists\mylists::callDeletefunction($aParam);
			}
		}
	
		if($bResult)
			$aResponse["message"] = "L'Article vient d'être supprimé";
		else
			$aResponse["message_err"] = "Paramètre manquant lors de la suppression de l'objet";

		return $aResponse;

	}

    public static function exportToPdf($aParam){

    if(isset($aParam['id_list']) && !empty($aParam['id_list'])){

        require_once('medias/libraries/tcpdf/tcpdf.php');

        $aRes = \models\mylists\mylists::getItemsFromIDList($aParam['id_list']);

        $tbl="";
        $tbl_header = '<h1>Export de la liste :</h1>
                        <table border="1">
							<thead>
								<tr>
							      <th class="lead" scope="col"><strong>Nom de la liste</strong></th>
							      <th class="lead" scope="col"><strong>Utilisateur</strong></th>
							      <th class="lead" scope="col"><strong>Objet(s)</strong></th>
							    </tr>
						    </thead>';
        $tbl_footer = '</table>';

        $oPdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $oPdf->SetAuthor('Wishlistcnam');
        $oPdf->SetTitle('Liste des objets');
        $oPdf->SetSubject('Wishlist NFE114');

        $oPdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $oPdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $oPdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        foreach($aRes as $row) {
            $tbl .= "<tr><td>".$row['username']."</td>";
            $tbl .= "<td>".$row['prenom']."</td>";
            $tbl .= "<td>".$row['name']."</td>";
            $tbl .= "</tr> ";
        }

        $oPdf->AddPage();
        $oPdf->writeHTML($tbl_header . $tbl . $tbl_footer, true, 0, true, 0);
        $oPdf->lastPage();

        ob_clean();
        $sUrl =  '/tmp/'.date('Y-m-d-s').'.pdf';
        $oPdf->Output($sUrl,'F');

        $oPdf = file_get_contents($sUrl);
        $sEncodedPdf = base64_encode($oPdf);

        return $sEncodedPdf;
    }
}
	public static function getLists($sParam='',$sSearch=''){

		$oResult = \models\mylists\mylists::getLists($sParam,$sSearch);
		
		return $oResult;
	}
	
	public static function addList($aParam){

		$aResponse = array();

		if(isset($_SESSION['id_user'],$aParam['listname']) and !empty($_SESSION['id_user']) and !empty($aParam['listname'])){
			$iUser = $_SESSION['id_user'];
			$bResult = \models\mylists\mylists::addList($aParam,$iUser);
		}

		if($bResult === true){
        	$aResponse['message'] = "Votre nouvelle liste a été crée";
		}
	   	else 
        	$aResponse['message_err'] = "Veuillez saisir un nom de liste";

		return $aResponse;
	}

	public static function removeList($aParam){

		$aResponse = array();
		
		if(isset($aParam['id_list']) and !empty($aParam['id_list'])){
			$aRes = \models\mylists\mylists::getListOwner($aParam);
			if($aRes['id_user'] == $_SESSION['id_user']){
				$bResult = \models\mylists\mylists::callDeletefunction($aParam);
				if(!empty($bResult))
        			$aResponse['message'] = "La liste vient d'être supprimée";
	   			else 
        			$aResponse['message_err'] = "La suppression de la liste a échouée";
			}else
				$aResponse["message_err"] = "Cette liste ne vous appartient pas";
		}
		return $aResponse;
	}
}
?>