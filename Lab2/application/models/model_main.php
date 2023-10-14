<?php

class Model_Main extends Model
{
	private static $PDO;
	public function __construct()
	{
		require_once "application/core/constant.php";
		Model_Main::$PDO = new PDO ("mysql:dbname=".dbname.";host=".dbhost,dbuser,dbpass);
	}
	public function get_data($data = null)
	{	
		$select="SELECT id, title, description, cost_price as price FROM tovar ORDER BY id DESC LIMIT 6";
		$PDOStatement = Model_Main::$PDO->prepare($select);
		$PDOStatement->execute();
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
	public static function get_photo($id)
	{
		$select="SELECT photo FROM tovar where id = :id";
		$PDOStatement = Model_Main::$PDO->prepare($select);
		$PDOStatement->bindParam(':id', $id, PDO::PARAM_INT);
		$PDOStatement->execute();
		$photo = $PDOStatement->fetch();
		return "<img alt='##title##' title='##title##' src='data:image/*;base64,".base64_encode($photo['photo'])."' />";
	}
}
?>