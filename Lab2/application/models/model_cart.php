<?php

class Model_Cart extends Model
{
	private static $PDO;
	public static $cart;
	public function __construct()
	{
		require_once "application/core/constant.php";
		Model_Cart::$PDO = new PDO ("mysql:dbname=".dbname.";host=".dbhost,dbuser,dbpass);
	}
	public function get_data($data = null)
	{	
		Model_Cart::$cart=$_SESSION['cart'];
		return $_SESSION['cart'];
	}
	public function set_order()
	{
		Model_Cart::$PDO->beginTransaction();
		try
		{
			$select="SELECT id FROM client where   email LIKE :email and phone LIKE :phone and last_name LIKE :last_name and first_name LIKE :first_name ";
			$PDOStatement = Model_Cart::$PDO->prepare($select);
			$PDOStatement->bindParam(':first_name', $_POST['firsname'], PDO::PARAM_STR);
			$PDOStatement->bindParam(':last_name', $_POST['lastname'], PDO::PARAM_STR);
			$PDOStatement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			$PDOStatement->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
			$PDOStatement->execute();
			$clientid = $PDOStatement->fetch();
			$clientid =$clientid['id'];
			if(empty($clientid))
			{
				$select="INSERT INTO  client (first_name, last_name, email, phone) values(:first_name, :last_name, :email, :phone)";
				$PDOStatement = Model_Cart::$PDO->prepare($select);
				$PDOStatement->bindParam(':first_name', $_POST['firsname'], PDO::PARAM_STR);
				$PDOStatement->bindParam(':last_name', $_POST['lastname'], PDO::PARAM_STR);
				$PDOStatement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
				$PDOStatement->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
				$PDOStatement->execute();
				$clientid = Model_Cart::$PDO->lastInsertId();
			}
			if(!empty($clientid))
			{
				$select="INSERT INTO `order`(`status`) VALUES (1)";
				$PDOStatement = Model_Cart::$PDO->prepare($select);
				$PDOStatement->execute();
				$orderid = Model_Cart::$PDO->lastInsertId();
			
				$select="INSERT INTO `client_orders`(`client`, `order`) VALUES (:client, :order)";
				$PDOStatement = Model_Cart::$PDO->prepare($select);
				$PDOStatement->bindParam(':client', $clientid, PDO::PARAM_INT);
				$PDOStatement->bindParam(':order', $orderid, PDO::PARAM_INT);
				$PDOStatement->execute();
				$clientorderid = Model_Cart::$PDO->lastInsertId();
			
				$length = count($_POST['ids']);
				$ids = $_POST['ids'];
				$counts = $_POST['count'];
				for($i=0;$i<$length;$i++)
				{
					$select="INSERT INTO `ordered_tovar`(`tovar`, `order`, `count`) VALUES (:tovar,:order,:count)";
					$PDOStatement = Model_Cart::$PDO->prepare($select);
					$PDOStatement->bindParam(':tovar', $ids[$i], PDO::PARAM_INT);
					$PDOStatement->bindParam(':order', $orderid, PDO::PARAM_INT);
					$PDOStatement->bindParam(':count', $counts[$i], PDO::PARAM_INT);
					$PDOStatement->execute();
					$ordertovarid = Model_Cart::$PDO->lastInsertId();
			
				}
			}
			unset($_SESSION['cart']);
			Model_Cart::$PDO->commit();
			return "<h3 class='twelve column'>Thanks for your order!<br/>expect to call the manager to confirm your order.</h3>";
		}
		catch(Exception $e)
		{
			Model_Cart::$PDO->rollBack();
			return "<h3 class='twelve column'>Sorry for the inconvenience but an error</h3>";
		}
	}
	public static function get_photo($id)
	{
		$select="SELECT photo FROM tovar where id = :id";
		$PDOStatement = Model_Cart::$PDO->prepare($select);
		$PDOStatement->bindParam(':id', $id, PDO::PARAM_INT);
		$PDOStatement->execute();
		$photo = $PDOStatement->fetch();
		return "<img alt='##title##' title='##title##' src='data:image/*;base64,".base64_encode($photo['photo'])."' />";
	}
}
?>