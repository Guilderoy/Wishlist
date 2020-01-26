<?php

/** Class database qui gere les méthodes d'appels aux reqst bdd */

class database{

	private $sDbname;
	private $sDbhost;
	private $sDbuser;
	private $sDbpassword;
	private $oPdo;

	public function __construct($sDbname,$sDbhost='localhost',$sDbuser='broknadmin',$sDbpassword='tP13Wax3Z0v4Im'){
		$this->sDbname = $sDbname;
		$this->sDbhost = $sDbhost;
		$this->sDbuser = $sDbuser;
		$this->sDbpassword = $sDbpassword;
	}

	private function getPDO(){
		if($this->oPdo === null){
			$aOption =array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
			$oPdo = new pdo('mysql:dbname='.$this->sDbname.';host:'.$this->sDbhost.';',$this->sDbuser,$this->sDbpassword,$aOption);
			$oPdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->oPdo = $oPdo;
		}
		return $this->oPdo;
	}

	public function query($sQuery){
		$oSql =  $this->getPDO()->query($sQuery);
		$oRes = $oSql->fetchAll(PDO::FETCH_OBJ);
		return $oRes;
	}
	
	public function prepare($sQuery,$aDatas,$sType = ''){

		$oSql =  $this->getPDO()->prepare($sQuery);
		$oRes = $oSql->execute($aDatas);

		switch ($sType) {
			case 'simple':
				$oRes = $oSql->fetch(PDO::FETCH_ASSOC);
				break;
			case 'all':
				$oRes = $oSql->fetchAll(PDO::FETCH_ASSOC);
				break;
			case 'objectlist':
				$oRes = $oSql->fetchAll(PDO::FETCH_OBJ);
				break;	
			default:
				
				break;
		}

		return $oRes;
	}

	public function lastInsertID(){
		$iId =  $this->getPDO()->lastInsertId();
		return $iId;
	}

}


?>