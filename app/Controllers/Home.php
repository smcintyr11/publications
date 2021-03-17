<?php namespace App\Controllers;

class Home extends BaseController
{
	/**
   * Name: index
   * Purpose: Generates the index page
   *
   * Parameters: None
   *
   * Returns: None
   */
	public function index()
	{
		// Set the last page
		$session = session();
		$session->destroy();
		$session->set('lastPage', 'Home::index');

		$data['title'] = "Index";
		echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('home/index.php', $data);
		echo view('templates/footer.php', $data);
	}
}
