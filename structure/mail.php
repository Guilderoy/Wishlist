<?php

/** Class mail qui contient la methode envoi de mail depuis l'application */

class mail{

	private $sEmail;
	private $sSubject;
	private $sMessage;
	private $sHeader;

	public function __construct($sEmail,$sSubject,$sMessage,$sHeader){
		$this->sEmail = $sEmail;
		$this->sSubject = $sSubject;
		$this->sMessage = $sMessage;
		$this->sHeader = $sHeader;
	}

	public function sendmail(){
		$bResult = mail($this->sEmail,$this->sSubject,$this->sMessage,$this->sHeader);
		return $bResult;
	}

}


?>