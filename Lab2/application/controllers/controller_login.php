<?php 	class Controller_Login extends Controller	{		public $model;    	public $view;        	function __construct()    	{        	$this->view = new View();        	$this->model = new Model_Login();    	}    	    	function action_index()    	{	    		$data=$this->model->get_data();        	$this->view->generate('login_view.php', 'template_view.php',$data);    	}    	function action_enter()    	{	    		$data=$this->model->set_data();        	$this->view->generate('login_view.php', 'template_view.php',$data);    	}	}?>