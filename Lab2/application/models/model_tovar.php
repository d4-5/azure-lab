<?php

class Model_Tovar extends Model
{
	private static $PDO;
	public static $pages=1;
	public static $current_page=1;
	public function __construct()
	{
		require_once "application/core/constant.php";
		Model_Tovar::$PDO = new PDO ("mysql:dbname=".dbname.";host=".dbhost,dbuser,dbpass);
	}
	public function get_data($data = null)
	{	
		$select="SELECT id, title, description, cost_price as price  FROM tovar ";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->execute();
		$data = $PDOStatement->fetchAll();
		$count=count($data)/6;
		if(count($data)%6>0)$count++;
		Model_Tovar::$pages=$count;
		return $data;
	}
	public static function get_photo($id)
	{
		$select="SELECT photo FROM tovar where id = :id";
		$PDOStatement = Model_Tovar::$PDO->prepare($select);
		$PDOStatement->bindParam(':id', $id, PDO::PARAM_INT);
		$PDOStatement->execute();
		$photo = $PDOStatement->fetch();
		return "<img alt='##title##' title='##title##' src='data:image/*;base64,".base64_encode($photo['photo'])."' />";
	}
	public function get_details($id)
	{
		$title = Route::$routes[4];
		$title = str_replace("-"," ",$title);
		$select= "SELECT id, title, description, cost_price as price  FROM tovar where id= :id and title LIKE :title ";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->bindParam(':id', $id, PDO::PARAM_INT);
		$PDOStatement->bindParam(':title', $title, PDO::PARAM_STR);
		$PDOStatement->execute();
		
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
	public function get_search($title)
	{
		//$title = str_replace('-',' ',$title);
		$title="LOST MINI DRIVER";
		$select= "SELECT id, title, description, cost_price as price  FROM tovar where title LIKE '%:title%' ";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->bindParam(':title', $title, PDO::PARAM_STR);
		$PDOStatement->execute();
		
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
	
	public static function get_count()
	{
		$select="SELECT count(*) as count FROM tovar";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->execute();
		$count = $PDOStatement->fetch();
		$data=(int)($count['count']/6);
		if(count($count)%6>0)$data++;
		return $data;
	}
	public function get_page($page)
	{
		Model_Tovar::$pages = Model_Tovar::get_count();
		Model_Tovar::$current_page=$data;
		$min =($page-1)*6;
		$max = $min+6;
		$select="SELECT id, title, description, cost_price as price  FROM tovar ORDER BY id DESC  LIMIT :min, :max ";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->bindParam(':min', $min, PDO::PARAM_INT);
		$PDOStatement->bindParam(':max', $max, PDO::PARAM_INT);
		$PDOStatement->execute();
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
	public function get_from_category($category)
	{
		$title = Route::$routes[4];
		$select= "SELECT tovar.id, tovar.title, tovar.description, tovar.cost_price as price  ".
					"FROM tovar, tovar_category, category".
					" where tovar_category.idtov = tovar.id and tovar_category.idcat = :idcat ".
					"and category.id = tovar_category.idcat and category.title LIKE :title ORDER BY id DESC";
		$PDOStatement = 	Model_Tovar::$PDO->prepare($select);
		$PDOStatement->bindParam(':idcat', $category, PDO::PARAM_INT);
		$PDOStatement->bindParam(':title', $title, PDO::PARAM_STR);
		$PDOStatement->execute();
		
		return $PDOStatement->fetchAll(PDO::FETCH_BOTH);
	}
}
?>