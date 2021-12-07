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

		$versionInfo = $this->getVersionInformation();

		$data['title'] = "Home Page";
		$data['updated'] = $versionInfo['Updated'];
		$data['version'] = $versionInfo['Version'];
		$data['description'] = $versionInfo['Description'];
		echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('home/index.php', $data);
		echo view('templates/footer.php', $data);
	}

	/**
	 * Name: index
	 * Purpose: Generates the index page
	 *
	 * Parameters: None
	 *
	 * Returns: None
	 */
	private function getVersionInformation()
	{
		// Load the query builder
		$db = \Config\Database::connect('publications');

		// Generate the query
		$builder = $db->table('Versions');
		$builder->orderBy('VersionID', 'DESC');

		// Return the result
		$result = $builder->get()->getRow();
		if (empty($result)) {
			return null;
		}
		$result = array('Updated' => $result->CreatedOn, 'Version' => $result->Version, 'Description' => $result->Description, );
		return $result;
	}
}
