<?php

namespace controller\items;

/** Controlleur qui appel mes modeles concernant la page item */

class items{
	
	public static function getItems($aParam=array()){

		$aBind = array();

		if(isset($aParam['limit']) && !empty($aParam['limit']))
			$aParam['limit'] = (int) $aParam['limit'];
		else
			$aParam['limit'] = (int) "6";

		if(isset($aParam['offset']) && !empty($aParam['offset']))
			$aParam['offset'] = (int) $aParam['offset'];
		else
			$aParam['offset'] = (int) "0";

		$oResult = \models\items\items::getLastItems($aParam);
		
		return $oResult;
	}

}