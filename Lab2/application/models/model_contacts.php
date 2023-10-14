<?php

class Model_Contact extends Model
{
	private static $PDO;
	public function __construct()
	{
		require_once "application/core/constant.php";
		Model_Contact::$PDO = new PDO ("mysql:dbname=".dbname.";host=".dbhost,dbuser,dbpass);
	}
	public function get_data($data = null)
	{	
		$select="SELECT contact_type.title, contact.contact FROM contact LEFT JOIN contact_type ON contact.type=contact_type.id";
		$PDOStatement = Model_Contact::$PDO->prepare($select);
		$PDOStatement->execute();
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
}
?>