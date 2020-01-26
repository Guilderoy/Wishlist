<?php

namespace models\items;

/** Modele qui gère mes requetes depuis le controller concernant la page items  */

class items{

	public function getURL(){
		return 'index.php?p=items&id=' . $this->id;
	}

	public static function getLastItems($aBind){
		
		$limit=$aBind['limit'];
		$offset=$aBind['offset'];

		$sSql = "SELECT * FROM wl_items ORDER BY id DESC LIMIT {$limit} OFFSET {$offset};";

		/** Je ne peux pas preparer ma requete avec LIMIT et OFFSET **/

		$oRes = \coredb::getDB()->query($sSql);
		return $oRes;
	}

	public static function getItemById($iItem){
		
		$sSql = "SELECT * FROM wl_items WHERE id=:id_item";

		/** Je ne peux pas preparer ma requete avec LIMIT et OFFSET **/

		$oRes = \coredb::getDB()->query($sSql);
		return $oRes;
	}

	/** Réserve un Item provenant d'une liste **/

	public static function reserveItem($aParam,$iSession){

		$aBind = array();
		$aBind['id_user'] = $iSession;
		$aBind['id_item'] = $aParam['id_item'];
		$aBind['id_list'] = $aParam['id_list'];

		$sSql ="UPDATE wl_link_lists
				SET reserved=:id_user
				WHERE id_lists =:id_list 
					AND id_items=:id_item;";

		$bRes = \coredb::getDB()->prepare($sSql,$aBind);

		return $bRes;
	}

	/** Libère la réservation **/ 

	public static function releaseItem($aParam,$sRelease){
		$bRes = self::reserveItem($aParam,$sRelease);
		return $bRes;
	}

	public static function getReservedItem($aParam){
		
		$aBind = array();
		$aBind['id_item'] = $aParam['id_item'];
		$aBind['id_list'] = $aParam['id_list'];

		$sSql="SELECT reserved
				FROM wl_link_lists AS link
				WHERE id_lists =:id_list 
					AND id_items=:id_item;";
		
		$aRes= \coredb::getDB()->prepare($sSql,$aBind,'simple');

		return $aRes;
	}

}