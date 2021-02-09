<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data['title'] = "Index";
		echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('home/index.php', $data);
		echo view('templates/footer.php', $data);
	}
}
