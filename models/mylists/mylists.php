<?php

namespace models\mylists;

/** Modele qui gère mes requetes depuis le controller concernant la page lists  */

class mylists{
    
	public static function getLists($sParam=array(),$sSearch=''){
		
		$aBind = array();
		$sWhere = "";

		if(isset($sParam) & !empty($sParam))
			$sType = 'all';
		else
			$sType = 'objectlist';

		if(isset($sSearch) and !empty($sSearch)){
			$aBind['name'] = $sSearch.'%';
			$sWhere = "WHERE li.`name` LIKE :name OR us.`firstname` LIKE :name  OR us.`username` LIKE :name ";
		}

		$sSql="SELECT li.`id`,name,firstname,us.`id` AS id_user 
					FROM wl_lists AS li
					LEFT JOIN wl_users AS us ON  us.`id` = li.`id_user`
					".$sWhere."
					ORDER BY id DESC;";
		
		$aRes = \coredb::getDB()->prepare($sSql,$aBind,$sType);
		return $aRes;
	}

	public static function getItemsLastList($iUser){
		
		$aBind = array();
		$aBind['id_list'] = self::getLastIdList($iUser);
		$aBind['id_user'] = $iUser;
	

		$sSql="SELECT li.`id`,li.`name`,us.`username`,it.`name`,it.`img`,it.`url`,it.`id` as id_item,link.`reserved`,
		us1.`firstname`,us1.`lastname`
					FROM wl_items as it
						LEFT JOIN wl_link_lists as link ON link.`id_items` = it.`id` 
					    LEFT JOIN wl_lists as li ON li.`id` = link.`id_lists`
					    LEFT JOIN wl_users as us ON us.`id` = li.`id_user`
					    LEFT JOIN wl_users as us1 ON us1.`id` = link.`reserved`
						WHERE id_lists != 0 AND us.`id` = :id_user AND li.`id` = :id_list;";
		
		$oRes = \coredb::getDB()->prepare($sSql,$aBind,'objectlist');
		return $oRes;
	}


	public static function getItemsFromIDList($iList,$iItem=""){
		
		$aBind = array();
		$aBind['id_list'] = $iList ;
		$sWhere = "";

		if(isset($iItem) and !empty($iItem)){
			$aBind['id_item'] = $iItem;
			$sWhere = "AND link.`id_items` = :id_item";
		}
		
		$sSql="SELECT li.`id`,li.`name`,us.`firstname` AS prenom, us.`username`,it.`name`,it.`img`,it.`url`,it.`id` as id_item,link.`reserved`,
		us1.`firstname`,us1.`lastname`,us.`id` as id_user_list
				FROM wl_items as it
					LEFT JOIN wl_link_lists as link ON link.`id_items` = it.`id` 
				    LEFT JOIN wl_lists as li ON li.`id` = link.`id_lists`
				    LEFT JOIN wl_users as us ON us.`id` = li.`id_user`
				    LEFT JOIN wl_users as us1 ON us1.`id` = link.`reserved`
					WHERE id_lists != 0 AND li.`id` = :id_list ".$sWhere.";";


		$aRes = \coredb::getDB()->prepare($sSql,$aBind,'all');
		return $aRes;
	}

	
	private static function getLastIdList(){
		
		$sSql="SELECT li.`id`
				FROM wl_lists AS li
				LEFT JOIN wl_users AS us ON  us.`id` = li.`id_user`
				ORDER BY id DESC;";
		
		$aRes= \coredb::getDB()->prepare($sSql,array(),'simple');

		return $aRes['id'];
	}

	public static function addList($aParam,$iSession){
		
		$aBind = array();
		$aBind['id_user'] = $iSession;
		$aBind['name'] = $aParam['listname'];
		
		$sSql="INSERT INTO wl_lists (name,id_user) VALUES (:name,:id_user)";
		
		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		return $bRes;
	}

	public static function addItemToList($aParam){
		
		$aBind = array();
		$aBind['item_name'] = $aParam['item_name'];
		$aBind['description'] = $aParam['description'];
		$aBind['fileurl'] = $aParam['fileurl'];
		$aBind['linkurl'] = $aParam['linkurl'];
		

		$sSql="INSERT INTO wl_items (name,description,img,url) 
				VALUES (:item_name,:description,:fileurl,:linkurl)";
		
		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		// On insère cet item à la liste selectionnée

		if($bRes){

			$iItem = "";

			unset($aBind['item_name'],$aBind['description'],$aBind['fileurl'],$aBind['linkurl']);

			$iItem = \coredb::getDB()->lastInsertID();

			$aBind['id_item'] = $iItem;
			$aBind['id_list'] = $aParam['add_select'];

			// Ajout l'item existant à la liste actuelle

			$bRes = self::addExistingItem($aBind);

		}

		return $bRes;
	}

	public static function addExistingItem($aParam){

		$sSql="INSERT INTO wl_link_lists (id_lists,id_items) VALUES (:id_list,:id_item)";

		$bRes = \coredb::getDB()->prepare($sSql,$aParam);

		return $bRes;
	}

	public static function getListOwner($aParam){

		$aBind = array();
		$aBind['id_list'] =  $aParam['id_list'];

		$sSql="SELECT li.`id_user`
				FROM wl_lists AS li
				WHERE id = :id_list;";
		
		$aRes= \coredb::getDB()->prepare($sSql,$aBind,'simple');

		return $aRes;

	} 

	public static function callDeletefunction($aParam){

		if(count($aParam) > 1)
			return $bRes = self::removeItemToList($aParam);
		else
			return $bRes = self::removeList($aParam);
	
	}

	private static function removeList($aParam){

		$aBind = array();

		$aBind['id_list'] = $aParam['id_list'];

		$sSql="DELETE FROM wl_lists WHERE id = :id_list";
		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		if($bRes){
			$sSql="DELETE FROM wl_link_lists WHERE id_lists = :id_list";
			$bRes = \coredb::getDB()->prepare($sSql,$aBind);
		}

		return $bRes;
	}

	private static function removeItemToList($aParam){

		$aBind = array();

		$aBind['id_item'] = $aParam['id_item'];
		$aBind['id_list'] = $aParam['id_list'];

		$sSql="DELETE FROM wl_link_lists WHERE id_lists = :id_list AND  id_items = :id_item";
		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		return $bRes;

	}

}
