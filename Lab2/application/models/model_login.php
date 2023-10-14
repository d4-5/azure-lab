<?php

class Model_Login extends Model
{
	private static $PDO;
	public function __construct()
	{
		require_once "application/core/constant.php";
		Model_Login::$PDO = new PDO ("mysql:dbname=".dbname.";host=".dbhost,dbuser,dbpass);
	}
	public function get_data($data = null)
	{	
	}
	public function set_data()
	{
		$pass=md5($_POST['password']);
		$select="SELECT user_status.title as status FROM user LEFT JOIN user_status ON user.statusid=user_status.id WHERE login LIKE :login and pass LIKE :pass";
		$PDOStatement = Model_Login::$PDO->prepare($select);
		$PDOStatement->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
		$PDOStatement->bindParam(':pass',  $pass, PDO::PARAM_STR);
		$PDOStatement->execute();
		$user = $PDOStatement->fetch();
		if(!empty($user))
		{
			$_SESSION['user']=$user['status'];
			return "true";
		}
		return "false";
	}
}
?>