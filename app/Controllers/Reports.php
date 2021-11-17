<?php namespace App\Controllers;

class Reports extends BaseController
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
		$session->set('lastPage', 'Reports::index');

		$data['title'] = "Reports";
		echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('reports/index.php', $data);
		echo view('templates/footer.php', $data);
	}
}
