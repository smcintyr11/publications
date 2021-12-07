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

		$versions = $this->getVersionInformation(5);

		$data['title'] = "Home Page";
		$data['versions'] = $versions;
		echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('home/index.php', $data);
		echo view('templates/footer.php', $data);
	}

	/**
	 * Name: getVersionInformation
	 * Purpose: Gets the last 5 rows from the version table
	 *
	 * Parameters:
	 *	$rows - The number of rows to get
	 *
	 * Returns:
	 *	Array of rows
	 */
	private function getVersionInformation($rows)
	{
		// Load the query builder
		$db = \Config\Database::connect('publications');

		// Generate the query
		$builder = $db->table('Versions');
		$builder->orderBy('VersionID', 'DESC');
		$builder->limit($rows);

		// Return the result
		$results = array();
		foreach ($builder->get()->getResult() as $row) {
				$r = array ('Updated' => $row->CreatedOn, 'Version' => $row->Version, 'Description' => $row->Description, );
				array_push($results, $r);
		}
		if (count($results) == 0) {
			return null;
		}
		return $results;
	}
}
