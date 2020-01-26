<?php

/** Class coredb qui gere l'acces a la BDD */

	class coredb{

		const DBNAME = 'db_wishlist';
		const DBHOST = '127.0.0.1';
		const DBUSER = 'root';
		const DBPASSWD = 'P0Z!Ok9y%2^%$oP1d@';

		private static $oDatabase;

		public static function getDB(){
			if(self::$oDatabase === null){
				self::$oDatabase = new database(self::DBNAME,self::DBHOST,self::DBUSER,self::DBPASSWD);
			}
			return self::$oDatabase;	
		}
	}

?>